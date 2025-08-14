@extends('layouts.app')

@section('content')
    <x-list-layout
    title="Lista de categorías"
    :items="$categories"
    :viewType="$viewType"
    rowView="partials.row"
    cardView="partials.card"
    :tableHeaders="['ID','Título','Estado','Acciones']"
    :tableFields="['id','name','completed']"
    :extraFields="['Estado' => 'completed']"
    emptyMessage="No hay categorías registradas."
    policyModel="App\Models\Category"
    createRoute="categories.create"
    editRoute="categories.edit"
    deleteRoute="categories.destroy"
    showRoute="categories.show"
    titleField="name"
    imageField="image"
    createText="Crear nueva categoría"
    indexRoute="categories.index"
    searchPlaceholder="Buscar categoría..."
/>

@endsection
