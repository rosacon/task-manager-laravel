@extends('layouts.app')

@section('content')
    <h1>Detalles de la categoría</h1>

    <p><strong>Título:</strong> {{ $category->name }}</p>    
    <p><strong>Creada en:</strong> {{ $category->created_at->format('d-m-Y H:i') }}</p>

    <a href="{{ route('categories.index') }}">Volver al listado</a>
@endsection
