@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Lista de Pokémon</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pokemons as $pokemon)
            <tr>
                <td><img src="{{ $pokemon['image'] }}" alt="{{ ucfirst($pokemon['name']) }}"></td>
                <td>{{ ucfirst($pokemon['name']) }}</td>
                <td><a href="{{ $pokemon['url'] }}" target="_blank">Ver detalles</a></td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No hay pokémon disponibles.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
        @if($hasPrevious)
        <a href="{{ route('pokemon.index', ['page' => $page - 1, 'per_page' => $perPage]) }}" class="btn btn-secondary">
            ← Anterior
        </a>
        @else
        <span></span>
        @endif

        @if($hasNext)
        <a href="{{ route('pokemon.index', ['page' => $page + 1, 'per_page' => $perPage]) }}" class="btn btn-primary">
            Siguiente →
        </a>
        @endif
    </div>



</div>
@endsection