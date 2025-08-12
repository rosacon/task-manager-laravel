@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Tareas</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @can('create', App\Models\Task::class)
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Crear Nueva Tarea</a>
    @endcan

     <form action="{{ route('tasks.index') }}" method="GET" class="d-flex mb-3" role="search">
        <label for="search" class="visually-hidden">Buscar tareas</label>
        <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar tarea..." class="form-control me-2" aria-label="Buscar tarea">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Completada</th>
               
                <th>Acciones</th>
                
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->completed ? 'Sí' : 'No' }}</td>
                @canany(['update', 'delete'], $task)
                <td>
                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endcan

                    @can('delete', $task)
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Está segura de eliminar esta tarea?')">
                            Eliminar
                        </button>
                    </form>
                    @endcan

                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-primary">Ver Tarea</a>
                </td>
                @endcanany
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay tareas registradas.</td>
            </tr>
            @endforelse
        </tbody>

    </table>
     {{ $tasks->links() }}
</div>
@endsection