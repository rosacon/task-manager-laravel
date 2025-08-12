@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="mb-4">Lista de Tareas</h1>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@auth
<a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Crear Nueva Tarea</a>
@endauth
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Completada</th>
            @auth
            <th>Acciones</th>
            @endauth
        </tr>
    </thead>
    <tbody>
        @forelse ($tasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->title }}</td>
            <td>{{ $task->completed ? 'Sí' : 'No' }}</td>
            @auth
            <td>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Editar</a>                
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Está segura de eliminar esta tarea?')">
                        Eliminar
                    </button>
                </form>

                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-primary">Ver Tarea</a>
            </td>
            @endauth
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">No hay tareas registradas.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection