@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-3">Prueba</h1>
    <h3>Fetch</h3>
    <button id="fetchGet">GET con Fetch</button><br>
    <button id="fetchPost">POST con Fetch</button><br>
    <button id="fetchPut">PUT con Fetch</button><br>
    <button id="fetchPatch">PATCH con Fetch</button><br>
    <button id="fetchDelete">DELETE con Fetch</button><br>

    <br>
    <h3>Axios</h3>
    <button id="axiosGet">GET con Axios</button><br>
    <button id="axiosPost">POST con Axios</button><br>
    <button id="axiosPut">PUT con Axios</button><br>
    <button id="axiosPatch">PATCH con Axios</button><br>
    <button id="axiosDelete">DELETE con Axios</button><br>

    <pre id="output"></pre>

    <script>
        const output = document.getElementById('output');

        // ------------------ FETCH ------------------

        // --- FETCH GET ---
        document.getElementById('fetchGet').addEventListener('click', async () => {
            try {
                const response = await fetch("https://jsonplaceholder.typicode.com/posts/1", {
                    method: "GET",
                    cache: "no-store"
                });
                const data = await response.json();
                output.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Fetch GET: ' + error;
            }
        });

        // --- FETCH POST ---
        document.getElementById('fetchPost').addEventListener('click', async () => {
            try {
                const response = await fetch('https://jsonplaceholder.typicode.com/posts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ title: 'foo', body: 'bar', userId: 1 })
                });
                const data = await response.json();
                output.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Fetch POST: ' + error;
            }
        });

        // --- FETCH PUT ---
        document.getElementById('fetchPut').addEventListener('click', async () => {
            try {
                const response = await fetch('https://jsonplaceholder.typicode.com/posts/1', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: 1, title: 'updated', body: 'new body', userId: 1 })
                });
                const data = await response.json();
                output.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Fetch PUT: ' + error;
            }
        });

        // --- FETCH PATCH ---
        document.getElementById('fetchPatch').addEventListener('click', async () => {
            try {
                const response = await fetch('https://jsonplaceholder.typicode.com/posts/1', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ title: 'patched title' })
                });
                const data = await response.json();
                output.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Fetch PATCH: ' + error;
            }
        });

        // --- FETCH DELETE ---
        document.getElementById('fetchDelete').addEventListener('click', async () => {
            try {
                const response = await fetch('https://jsonplaceholder.typicode.com/posts/1', {
                    method: 'DELETE'
                });
                output.textContent = 'Fetch DELETE: status ' + response.status;
            } catch (error) {
                output.textContent = 'Error en Fetch DELETE: ' + error;
            }
        });


        // ------------------ AXIOS ------------------

        // --- AXIOS GET ---
        document.getElementById('axiosGet').addEventListener('click', async () => {
            try {
                const response = await axios.get(`https://jsonplaceholder.typicode.com/posts/1?t=${Date.now()}`);
                output.textContent = JSON.stringify(response.data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Axios GET: ' + error;
            }
        });

        // --- AXIOS POST ---
        document.getElementById('axiosPost').addEventListener('click', async () => {
            try {
                const response = await axios.post('https://jsonplaceholder.typicode.com/posts', {
                    title: 'foo',
                    body: 'bar',
                    userId: 1
                });
                output.textContent = JSON.stringify(response.data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Axios POST: ' + error;
            }
        });

        // --- AXIOS PUT ---
        document.getElementById('axiosPut').addEventListener('click', async () => {
            try {
                const response = await axios.put('https://jsonplaceholder.typicode.com/posts/1', {
                    id: 1,
                    title: 'updated',
                    body: 'new body',
                    userId: 1
                });
                output.textContent = JSON.stringify(response.data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Axios PUT: ' + error;
            }
        });

        // --- AXIOS PATCH ---
        document.getElementById('axiosPatch').addEventListener('click', async () => {
            try {
                const response = await axios.patch('https://jsonplaceholder.typicode.com/posts/1', {
                    title: 'patched title'
                });
                output.textContent = JSON.stringify(response.data, null, 2);
            } catch (error) {
                output.textContent = 'Error en Axios PATCH: ' + error;
            }
        });

        // --- AXIOS DELETE ---
        document.getElementById('axiosDelete').addEventListener('click', async () => {
            try {
                const response = await axios.delete('https://jsonplaceholder.typicode.com/posts/1');
                output.textContent = 'Axios DELETE: status ' + response.status;
            } catch (error) {
                output.textContent = 'Error en Axios DELETE: ' + error;
            }
        });

    </script>
</div>
@endsection
