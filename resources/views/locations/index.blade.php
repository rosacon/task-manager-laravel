@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Seleccionar ubicación</h1>

    <div class="mb-3">
        <label for="country" class="form-label">País</label>
        <select name="country" id="country" class="form-control">
            <option value="">Seleccione un país</option>
            @foreach($countries as $country)
            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
            @endforeach
        </select>

        <!-- Botón para refrescar países -->
        <a href="#" id="refreshCountriesBtn" class="btn btn-primary">
            Refrescar países
        </a>
    </div>

    <div class="mb-3">
        <label for="state" class="form-label">Departamento / Estado</label>
        <select id="state" class="form-select" disabled>
            <option value="">Seleccione un departamento</option>
        </select>

        <!-- Botón para refrescar estados -->
        <a href="#" id="refreshStatesBtn" class="btn btn-secondary" disabled>
            Refrescar estados
        </a>
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Ciudad</label>
        <select id="city" class="form-select" disabled>
            <option value="">Seleccione una ciudad</option>
        </select>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const refreshStatesBtn = document.getElementById('refreshStatesBtn');
    const refreshCountriesBtn = document.getElementById('refreshCountriesBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    async function postJson(url, payload) {
        return await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });
    }

    function setLoading(selectEl, label = 'Cargando...') {
        selectEl.innerHTML = `<option value="">${label}</option>`;
        selectEl.disabled = true;
    }

    function setEmpty(selectEl, label = 'No hay opciones') {
        selectEl.innerHTML = `<option value="">${label}</option>`;
        selectEl.disabled = true;
    }

    // Función para cargar estados en el select
    async function loadStates(countryName) {
        setLoading(stateSelect, 'Cargando estados...');
        setEmpty(citySelect, 'Seleccione una ciudad');

        if (!countryName) {
            setEmpty(stateSelect, 'Seleccione un departamento');
            return;
        }

        try {
            const res = await postJson('{{ route("locations.states") }}', { country: countryName });
            if (!res.ok) throw new Error('HTTP ' + res.status);

            const data = await res.json();
            if (!Array.isArray(data) || data.length === 0) {
                setEmpty(stateSelect, 'No se encontraron estados');
                return;
            }

            stateSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
            data.forEach(st => {
                const original = st.original ?? '';
                const display = st.display ?? original;
                if (!original) return;
                const opt = document.createElement('option');
                opt.value = original;
                opt.textContent = display;
                stateSelect.appendChild(opt);
            });

            stateSelect.disabled = false;
        } catch (err) {
            console.error('Error cargando estados:', err);
            setEmpty(stateSelect, 'Error cargando estados');
        }
    }

    // Función para cargar ciudades en el select
    async function loadCities(countryName, stateName) {
        setLoading(citySelect, 'Cargando ciudades...');

        if (!stateName) {
            setEmpty(citySelect, 'Seleccione una ciudad');
            return;
        }

        try {
            const res = await postJson('{{ route("locations.cities") }}', { country: countryName, state: stateName });
            if (!res.ok) throw new Error('HTTP ' + res.status);

            const data = await res.json();
            if (!Array.isArray(data) || data.length === 0) {
                setEmpty(citySelect, 'No se encontraron ciudades');
                return;
            }

            citySelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
            data.forEach(ci => {
                const name = typeof ci === 'string' ? ci : (ci.name ?? ci.city ?? '');
                if (name) citySelect.innerHTML += `<option value="${name}">${name}</option>`;
            });
            citySelect.disabled = false;
        } catch (err) {
            console.error('Error cargando ciudades:', err);
            setEmpty(citySelect, 'Error cargando ciudades');
        }
    }

    // Evento al cambiar país
    countrySelect.addEventListener('change', function() {
        const countryName = this.value;

        // Actualiza botón de refrescar estados
        if (countryName) refreshStatesBtn.removeAttribute('disabled');
        else refreshStatesBtn.setAttribute('disabled', true);

        loadStates(countryName);
    });

    // Evento para refrescar estados (SPA)
    refreshStatesBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        const countryName = countrySelect.value;
        if (!countryName) return;

        try {
            // Llamada al backend para refrescar cache de estados
            const res = await fetch(`/locations/refresh-states/${countryName}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);

            await loadStates(countryName);
            alert('Estados refrescados correctamente');
        } catch (err) {
            console.error('Error refrescando estados:', err);
            alert('No se pudieron refrescar los estados');
        }
    });

    // Evento para refrescar países (SPA)
    refreshCountriesBtn.addEventListener('click', async function(e) {
        e.preventDefault();

        try {
            const res = await fetch('{{ route("locations.refresh") }}', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);

            const data = await res.json(); // Se espera array de países [{name:..., code:...}]
            countrySelect.innerHTML = '<option value="">Seleccione un país</option>';
            data.forEach(c => {
                countrySelect.innerHTML += `<option value="${c.name}">${c.name}</option>`;
            });

            alert('Países refrescados correctamente');

            // Resetea selects de estado y ciudad
            setEmpty(stateSelect, 'Seleccione un departamento');
            setEmpty(citySelect, 'Seleccione una ciudad');
            refreshStatesBtn.setAttribute('disabled', true);

        } catch (err) {
            console.error('Error refrescando países:', err);
            alert('No se pudieron refrescar los países');
        }
    });

    // Evento al cambiar estado
    stateSelect.addEventListener('change', function() {
        const countryName = countrySelect.value;
        const stateName = stateSelect.value;
        loadCities(countryName, stateName);
    });
});
</script>
@endsection