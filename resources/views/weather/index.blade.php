@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Consultar clima</h1>

    <form action="{{ route('weather.get') }}" method="GET">
        <label for="city">Ciudad:</label>
        <input type="text" id="city" name="city" value="{{ request('city') }}" required>
        <button type="submit">Consultar</button>
    </form>


    @if(session('errors'))
    <div style="color: red;">
        <ul>
            @foreach(session('errors')->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @isset($weather)
    <h2>Clima en {{ $weather['name'] }}, {{ $weather['sys']['country'] }}</h2>
    <p><strong>Temperatura:</strong> {{ $weather['main']['temp'] }}°C</p>
    <p><strong>Descripción:</strong>
        @foreach($weather['weather'] as $condition)
        {{ $condition['description'] }}@if(!$loop->last), @endif
        @endforeach
    </p>
    <p><strong>Humedad:</strong> {{ $weather['main']['humidity'] }}%</p>
    <p><strong>Viento:</strong> {{ $weather['wind']['speed'] }} m/s</p>
    @endisset
</div>
@endsection