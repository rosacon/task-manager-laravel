@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Seleccione un país</h1>

    <select name="country_id" id="country-select">
        @foreach($data as $country)
        <option value="{{ $country['iso2'] }}">
            {{ $country['name'] }}
        </option>
        @endforeach
    </select>

    <select name="state_id" id="state-select">
        <option value="">Seleccione un estado</option>
    </select>

    <select name="city_id" id="city-select">
        <option value="">Seleccione una ciudad</option>
    </select>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country-select');
        const stateSelect = document.getElementById('state-select');
        const citySelect = document.getElementById('city-select');

        // 1. Cuando selecciona país -> cargar estados
        countrySelect.addEventListener('change', function() {
            const countryIso2 = this.value;

            stateSelect.innerHTML = '<option>Cargando...</option>';
            citySelect.innerHTML = '<option value="">Seleccione un estado primero</option>';

            fetch(`/csc/states/${countryIso2}`)
                .then(response => response.json())
                .then(data => {
                    stateSelect.innerHTML = '';
                    if (data.ok && data.data.length > 0) {
                        data.data.forEach(state => {
                            const option = document.createElement('option');
                            option.value = state.iso2; // 👈 importante: iso2, no id
                            option.text = state.name;
                            stateSelect.appendChild(option);
                        });
                    } else {
                        stateSelect.innerHTML = '<option>No hay estados disponibles</option>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    stateSelect.innerHTML = '<option>Error al cargar</option>';
                });
        });

        // 2. Cuando selecciona estado -> cargar ciudades
        stateSelect.addEventListener('change', function() {
            const countryIso2 = countrySelect.value;
            const stateIso2 = this.value;

            citySelect.innerHTML = '<option>Cargando...</option>';

            fetch(`/csc/cities/${countryIso2}/${stateIso2}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '';
                    if (data.ok && data.data.length > 0) {
                        data.data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.iso2; // 👈 igual que en states, usar iso2
                            option.text = city.name;
                            citySelect.appendChild(option);
                        });
                    } else {
                        citySelect.innerHTML = '<option>No hay ciudades disponibles</option>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    citySelect.innerHTML = '<option>Error al cargar</option>';
                });
        });
    });
</script>

@endsection