<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ApiTestTryCatchController extends Controller
{
    public function getData()
    {
        $start = microtime(true); // Para medir el tiempo

        try {
            // Aquí intento hacer algo que puede fallar
            $response = Http::get('https://pokeapi.co/api/v2/pokemon'); // corregí el typo en la URL
            $data = $response->json();
            return response()->json($data);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo conectar con la API.'], 500);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Error en la respuesta: ' . $e->getMessage());
            return response()->json(['error' => 'La API devolvió un error.'], 502);

        } catch (\Throwable $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un problema interno.'], 500);

        } finally {
            $end = microtime(true);
            $duration = round($end - $start, 3);
            Log::info("Petición a la API finalizada. Duración: {$duration} segundos");
        }
    }
}
