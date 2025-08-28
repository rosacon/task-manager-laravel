@extends('layouts.app')

@section('content')

<div class="container py-4">
    <h1 class="mb-3">Prueba</h1>
    <label for="fruit">Fruta:</label>
    <select id="fruit">
        <option value="">Seleccione una fruta</option>
        <option value="citrus">Cítricos</option>
        <option value="berry">Bayas</option>
    </select>

    <label for="variety">Variedad:</label>
    <select id="variety" disabled>
        <option value="">Seleccione una variedad</option>
    </select>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fruitSelect = document.getElementById('fruit');
            const varietySelect = document.getElementById('variety');

            const options = {
                citrus: ['Naranja', 'Limón', 'Mandarina'],
                berry: ['Fresa', 'Arándano', 'Frambuesa']
            };

            fruitSelect.addEventListener('change', function() {
                const selected = this.value;

                // Limpiar select de variedad
                varietySelect.innerHTML = '<option value="">Seleccione una variedad</option>';

                if (!selected) {
                    varietySelect.disabled = true;
                    return;
                }

                // Llenar select de variedad según fruta seleccionada
                options[selected].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item;
                    opt.textContent = item;
                    varietySelect.appendChild(opt);
                });

                varietySelect.disabled = false;
            });
        });
    </script>



</div>
@endsection