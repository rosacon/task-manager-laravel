<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PruebaPaisesController extends Controller
{
    public function index(Request $request)
    {
        $paises = Cache::remember('paises', 60, function () {
            return ['Colombia', 'México', 'Argentina', 'Chile', 'Perú'];
        });

        // Pasamos $paises a la vista
        return view('pruebapaises.index', compact('paises'));
    }

    public function refresh(Request $request)
    {
        Cache::forget('paises');

        $paises = Cache::remember('paises', 60, function () {
            return ['Colombia', 'México', 'Argentina', 'Chile', 'Perú', 'Brasil', 'Uruguay'];
        });

        return response()->json([
            'message' => 'Cache refrescada',
            'data' => $paises
        ]);
    }
}
