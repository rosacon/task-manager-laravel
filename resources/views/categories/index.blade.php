@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Categorías</h1>
    @auth
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Nueva Categoría</a>
    @endauth
    <form action="{{ route('categories.index') }}" method="GET" class="d-flex mb-3" role="search">
        <label for="search" class="visually-hidden">Buscar categoría</label>
        <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar categoría..." class="form-control me-2" aria-label="Buscar categoría">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                @auth
                <th>Acciones</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                @auth
                <td>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-primary">Ver Categoria</a>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection