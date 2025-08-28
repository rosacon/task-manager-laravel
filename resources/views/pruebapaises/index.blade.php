@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Lista de Países (desde Cache)</h1>

    <ul id="paises-lista">
        @foreach($paises as $pais)
        <li>{{ $pais }}</li>
        @endforeach
    </ul>

    <button id="refreshBtn">Refrescar Cache</button>

    <script>
        document.getElementById('refreshBtn').addEventListener('click', async () => {
            try {
                const response = await fetch("{{ route('pruebapaises.refresh') }}");
                const data = await response.json();

                const lista = document.getElementById('paises-lista');
                lista.innerHTML = "";
                data.data.forEach(pais => {
                    const li = document.createElement('li');
                    li.textContent = pais;
                    lista.appendChild(li);
                });
            } catch (error) {
                console.error("Error al refrescar cache", error);
            }
        });
    </script>


</div>
@endsection