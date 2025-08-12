@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva Categoría</h1>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button class="btn btn-success">Guardar</button>
    </form>
</div>
@endsection
