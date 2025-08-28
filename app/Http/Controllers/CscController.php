<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class CscController extends Controller
{
    private function http()
    {
        $baseUrl = Config::get('services.csc.base_url');
        $apiKey  = Config::get('services.csc.key');

        // Validación temprana (por si falta la API Key)
        if (!$apiKey) {
            throw new \RuntimeException('Falta configurar CSC_API_KEY en .env');
        }

        return Http::withHeaders([
            'X-CSCAPI-KEY' => $apiKey, // header típico de CSC
            'Accept'       => 'application/json',
        ])
            ->baseUrl($baseUrl)
            ->timeout(12)
            ->retry(2, 300);
    }

    public function countries()
    {
        $start = microtime(true);

        try {
            $res  = $this->http()->get('/countries')->throw();
            $data = $res->json();

            /*return response()->json([
                'ok'    => true,
                'count' => is_array($data) ? count($data) : null,
                'data'  => $data,
            ]);*/

            return view('csc.countries', compact('data'));
        } catch (ConnectionException $e) {
            Log::warning('CSC countries: conexión fallida', ['msg' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'No hay conexión con la API'], 500);
        } catch (RequestException $e) {
            Log::error('CSC countries: respuesta HTTP inválida', [
                'status' => optional($e->response)->status(),
                'body'   => optional($e->response)->body(),
            ]);
            return response()->json(['ok' => false, 'error' => 'La API devolvió un error'], 502);
        } catch (\Throwable $e) {
            Log::error('CSC countries: error inesperado', ['msg' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'Error interno'], 500);
        } finally {
            $dur = round(microtime(true) - $start, 3);
            Log::info("CSC countries finalizado en {$dur}s");
        }

                   
        
    }

    public function states(string $countryIso2)
    {
        $start = microtime(true);

        try {
            $res  = $this->http()->get("/countries/{$countryIso2}/states")->throw();
            $data = $res->json();

            return response()->json([
                'ok'    => true,
                'count' => is_array($data) ? count($data) : null,
                'data'  => $data,
            ]);
            
        } catch (ConnectionException $e) {
            Log::warning('CSC states: conexión fallida', ['country' => $countryIso2, 'msg' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'No hay conexión con la API'], 500);
        } catch (RequestException $e) {
            Log::error('CSC states: respuesta HTTP inválida', [
                'country' => $countryIso2,
                'status'  => optional($e->response)->status(),
                'body'    => optional($e->response)->body(),
            ]);
            return response()->json(['ok' => false, 'error' => 'La API devolvió un error'], 502);
        } catch (\Throwable $e) {
            Log::error('CSC states: error inesperado', ['country' => $countryIso2, 'msg' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'Error interno'], 500);
        } finally {
            $dur = round(microtime(true) - $start, 3);
            Log::info("CSC states({$countryIso2}) finalizado en {$dur}s");
        }
    }

    public function cities(string $countryIso2, string $stateIso2)
    {
        $start = microtime(true);

        try {
            $res  = $this->http()->get("/countries/{$countryIso2}/states/{$stateIso2}/cities")->throw();
            $data = $res->json();

            return response()->json([
                'ok'    => true,
                'count' => is_array($data) ? count($data) : null,
                'data'  => $data,
            ]);
        } catch (ConnectionException $e) {
            Log::warning('CSC cities: conexión fallida', [
                'country' => $countryIso2,
                'state' => $stateIso2,
                'msg' => $e->getMessage()
            ]);
            return response()->json(['ok' => false, 'error' => 'No hay conexión con la API'], 500);
        } catch (RequestException $e) {
            Log::error('CSC cities: respuesta HTTP inválida', [
                'country' => $countryIso2,
                'state'   => $stateIso2,
                'status'  => optional($e->response)->status(),
                'body'    => optional($e->response)->body(),
            ]);
            return response()->json(['ok' => false, 'error' => 'La API devolvió un error'], 502);
        } catch (\Throwable $e) {
            Log::error('CSC cities: error inesperado', [
                'country' => $countryIso2,
                'state' => $stateIso2,
                'msg' => $e->getMessage()
            ]);
            return response()->json(['ok' => false, 'error' => 'Error interno'], 500);
        } finally {
            $dur = round(microtime(true) - $start, 3);
            Log::info("CSC cities({$countryIso2},{$stateIso2}) finalizado en {$dur}s");
        }
    }
}
