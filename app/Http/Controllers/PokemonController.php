<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $perPage = max(1, min($perPage, 5));

        // Obtener lista básica
        $response = Http::get("https://pokeapi.co/api/v2/pokemon", [
            'offset' => $offset,
            'limit' => $perPage
        ]);

        if ($response->failed()) {
            return view('pokemons.index', [
                'pokemons' => [],
                'page' => $page,
                'perPage' => $perPage,
                'error' => 'No se pudo obtener la lista de Pokémon'
            ]);
        }

        $results = $response->json()['results'];

        // Peticiones en paralelo para detalles
        $pokemonDetails = Http::pool(
            fn($pool) =>
            collect($results)->map(
                fn($pokemon) => $pool->as($pokemon['name'])->get($pokemon['url'])
            )->toArray()
        );

        // Procesar datos
        $pokemons = collect($pokemonDetails)->map(fn($detail) => [
            'name' => $detail->json()['name'],
            'image' => $detail->json()['sprites']['front_default'],
            'height' => $detail->json()['height'],
            'weight' => $detail->json()['weight'],
            'url' => 'https://pokeapi.co/api/v2/pokemon/' . $detail->json()['id']
        ]);

        // Pasar datos a la vista
        return view('pokemon.index', [
            'pokemons' => $pokemons,
            'page' => $page,
            'perPage' => $perPage,
            'hasNext' => !empty($response->json()['next']),
            'hasPrevious' => !empty($response->json()['previous'])
        ]);
    }
}
