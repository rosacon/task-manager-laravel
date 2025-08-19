<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /** ==============================
     *  Constantes de endpoints (evita “strings mágicos”)
     *  ============================== */
    private const BASE_URL      = 'https://countriesnow.space/api/v0.1';
    private const URL_COUNTRIES = self::BASE_URL . '/countries/positions';
    private const URL_STATES    = self::BASE_URL . '/countries/states';
    private const URL_CITIES    = self::BASE_URL . '/countries/state/cities';

    /** ==============================
     *  Helper HTTP (timeout/reintentos)
     *  ============================== */
    private function http()
    {
        // 12s de timeout, 2 reintentos con 300ms de espera
        return Http::timeout(12)->retry(2, 300);
    }

    /** ==============================
     *  Vista principal: trae países (sin cache por ahora)
     *  ============================== */
    public function index()
    {
        try {
            $countries = Cache::remember('location.countries', now()->addDay(), function () {
                $json = $this->http()->get(self::URL_COUNTRIES)->throw()->json();
                return $json['data'] ?? [];
            });
        } catch (ConnectionException $e) {
            Log::error('Fallo de conexión al obtener países', ['error' => $e->getMessage()]);
            $countries = [];
        } catch (\Throwable $e) {
            Log::error('Error al obtener países', ['error' => $e->getMessage()]);
            $countries = [];
        }

        return view('locations.index', compact('countries'));
    }


    /** ==============================
     *  Estados por país (POST {country: "Colombia"})
     *  Devuelve: array de estados (para tu JS)
     *  ============================== */
    public function getStates(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string',
        ]);

        try {
            // Cacheamos por país
            $states = Cache::remember(
                'location.states.' . $validated['country'],
                now()->addDay(),
                function () use ($validated) {
                    $json = $this->http()->post(self::URL_STATES, [
                        'country' => $validated['country'],
                    ])->throw()->json();

                    // Normalizamos aquí mismo antes de guardar en cache
                    return $this->extractStates($json);
                }
            );

            return response()->json($states);
        } catch (ConnectionException $e) {
            Log::warning('Fallo de conexión al obtener estados', [
                'country' => $validated['country'],
                'error' => $e->getMessage()
            ]);
            return $this->serverError('No se pudieron obtener los estados (conexión).');
        } catch (\Throwable $e) {
            Log::error('Error al obtener estados', [
                'country' => $validated['country'],
                'error' => $e->getMessage()
            ]);
            return $this->serverError('No se pudieron obtener los estados.');
        }
    }

    public function refreshStates($country)
    {
        try {
            Cache::forget('location.states.' . $country);

            $json = $this->http()->post(self::URL_STATES, [
                'country' => $country,
            ])->throw()->json();

            $states = $this->extractStates($json);

            Cache::put('location.states.' . $country, $states, now()->addDay());

            return back()->with('status', 'Estados refrescados correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error al refrescar estados', [
                'country' => $country,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'No se pudieron refrescar los estados.');
        }
    }



    /** ==============================
     *  Ciudades por país/estado (POST {country, state})
     *  Devuelve: array de ciudades (para tu JS)
     *  ============================== */
    public function getCities(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string',
            'state'   => 'required|string',
        ]);

        try {
            $json = $this->http()
                ->post(self::URL_CITIES, [
                    'country' => $validated['country'],
                    'state'   => $validated['state'],
                ])->throw()->json();

            $cities = $this->extractCities($json); // normalizamos
            return response()->json($cities);
        } catch (ConnectionException $e) {
            Log::warning('Fallo de conexión al obtener ciudades', [
                'country' => $validated['country'],
                'state'   => $validated['state'],
                'error'   => $e->getMessage()
            ]);
            return $this->serverError('No se pudieron obtener las ciudades (conexión).');
        } catch (\Throwable $e) {
            Log::error('Error al obtener ciudades', [
                'country' => $validated['country'],
                'state'   => $validated['state'],
                'error'   => $e->getMessage()
            ]);
            return $this->serverError('No se pudieron obtener las ciudades.');
        }
    }

    /** ==============================
     *  Normalizadores de respuesta
     *  ============================== */
    private function extractStates(array $json): array
    {
        $data = $json['data'] ?? [];

        $states = [];
        if (isset($data['states']) && is_array($data['states'])) {
            $states = $data['states'];
        } elseif (is_array($data)) {
            $states = $data;
        }

        $mapped = array_map(function ($state) {
            // Normalizamos según el tipo recibido
            if (is_string($state)) {
                $name = $state;
            } elseif (is_array($state)) {
                // Algunos vienen como ['name' => 'X'] o ['state_name' => 'X']
                $name = $state['name'] ?? $state['state_name'] ?? null;
            } else {
                // Caso raro: objeto u otro tipo → lo descartamos
                $name = null;
            }

            if (!$name) {
                return null;
            }

            return [
                'original' => $name,
                'display'  => $this->cleanDivisionName($name),
            ];
        }, $states);

        // Eliminamos los nulls (descartados)
        $mapped = array_filter($mapped);

        // Quitamos duplicados
        $unique = [];
        foreach ($mapped as $st) {
            $unique[$st['original']] = $st;
        }

        return array_values($unique);
    }
    private function extractCities(array $json): array
    {
        // Suele venir como { data: ["Bogota","Medellin", ...] } o { data: [{name:...}, ...] }
        $data = $json['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    /** ==============================
     *  Respuesta de error estándar (500)
     *  ============================== */
    private function serverError(string $message)
    {
        return response()->json([
            'error'   => true,
            'message' => $message,
        ], 500);
    }

    private function cleanDivisionName(string $name): string
    {
        // Elimina sufijos como "Department", "Province", "State", etc.
        return preg_replace('/\s+(Department|Province|State|County|District|Prefecture|Parish)$/i', '', $name);
    }

    public function refreshCountries(Request $request)
    {
        Cache::forget('countries_list');

        $json = $this->http()->get(self::URL_COUNTRIES)->throw()->json();
        $countries = $json['data'] ?? [];

        Cache::put('countries_list', $countries, now()->addDay());

        // Si la petición espera JSON (SPA)
        if ($request->wantsJson()) {
            return response()->json($countries);
        }

        // Si es navegador normal
        return redirect()->route('locations.index')->with('status', 'Lista de países actualizada');
    }

    public function refreshCountriesJson()
    {
        Cache::forget('countries_list');

        $json = $this->http()->get(self::URL_COUNTRIES)->throw()->json();
        $countries = $json['data'] ?? [];

        Cache::put('countries_list', $countries, now()->addDay());

        return response()->json($countries);
    }
}
