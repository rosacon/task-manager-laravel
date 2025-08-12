@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear nueva tarea</h1>

    {{-- Mostrar errores si los hay --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <strong>¡Ups!</strong> Hubo algunos problemas con los datos ingresados.
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control">
            @error('title')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="completed" id="completed" class="form-check-input"
                {{ old('completed') ? 'checked' : '' }}>
            <label for="completed" class="form-check-label">¿Completada?</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection