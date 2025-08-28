@extends('layouts.app')

@section('content')
    <x-list-layout
    title="Lista de tasks"
    :items="$tasks"
    :viewType="$viewType"
    rowView="partials.row"
    cardView="partials.card"
    :tableHeaders="['ID','Título','Estado','Acciones']"
    :tableFields="['id','title','completed']"
    :extraFields="['Estado' => 'completed']"
    emptyMessage="No hay tasks registradas."
    policyModel="App\Models\Category"
    createRoute="tasks.create"
    editRoute="tasks.edit"
    deleteRoute="tasks.destroy"
    showRoute="tasks.show"
    titleField="title"
    imageField="image"
    createText="Crear nueva task"
    indexRoute="tasks.index"
    searchPlaceholder="Buscar task..."
/>

@endsection
