@extends('layouts.app')

@section('content')
    <h1>Detalles de la tarea</h1>

    <p><strong>Título:</strong> {{ $task->title }}</p>
    <p><strong>Descripción:</strong> {{ $task->description }}</p>
    <p><strong>Creada en:</strong> {{ $task->created_at->format('d-m-Y H:i') }}</p>

    <a href="{{ route('tasks.index') }}">Volver al listado</a>
@endsection
