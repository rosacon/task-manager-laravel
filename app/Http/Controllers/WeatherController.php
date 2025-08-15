<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather.index');
    }

    public function getWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = $request->input('city');
        $apiKey = env('WEATHER_API_KEY');
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=es";

        $response = Http::get($url);

        if ($response->successful()) {
            $weather = $response->json();
            return view('weather.index', compact('weather'));
        } else {
            return back()->withErrors(['city' => 'Ciudad no encontrada o error en la API']);
        }
    }
}
