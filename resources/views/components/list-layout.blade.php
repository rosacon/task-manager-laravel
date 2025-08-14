<div>
    {{-- Título --}}
    <h1 class="mb-4 h1">{{ $title }}</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Acciones arriba --}}
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        @can('create', $policyModel)
            <a href="{{ route($createRoute) }}" class="btn btn-primary">
                {{ $createText ?? 'Crear nuevo' }}
            </a>
        @endcan

        {{-- Formulario de búsqueda --}}
        <form action="{{ route($indexRoute) }}" method="GET" class="d-flex ms-2" role="search">
            <input id="search" type="search" name="search" value="{{ request('search') }}"
                   placeholder="{{ $searchPlaceholder ?? 'Buscar...' }}" class="form-control me-2">
            <input type="hidden" name="view" value="{{ $viewType ?? 'cards' }}">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </form>

        {{-- Selector de vista --}}
        <div class="ms-auto">
            <a href="{{ route($indexRoute, array_merge(request()->query(), ['view' => 'cards'])) }}"
               class="btn btn-sm {{ $viewType === 'cards' ? 'btn-primary' : 'btn-outline-primary' }}">
                Tarjetas
            </a>
            <a href="{{ route($indexRoute, array_merge(request()->query(), ['view' => 'table'])) }}"
               class="btn btn-sm {{ $viewType === 'table' ? 'btn-primary' : 'btn-outline-primary' }}">
                Tabla
            </a>
        </div>
    </div>

    {{-- Contenido --}}
    @if($items->count())
        @if($viewType === 'table')
            {{-- Vista en tabla --}}
            @include($rowView, [
                'items'        => $items,
                'tableHeaders' => $tableHeaders,
                'tableFields'  => $tableFields,
                'editRoute'    => $editRoute,
                'deleteRoute'  => $deleteRoute
            ])
        @else
            {{-- Vista en tarjetas --}}
            <div class="row">
                @foreach($items as $item)
                    @include($cardView, [
                        'item'         => $item,
                        'titleField'   => $titleField,
                        'imageField'   => $imageField,
                        'extraFields'  => $extraFields,
                        'showRoute'    => $showRoute,
                        'editRoute'    => $editRoute,
                        'deleteRoute'  => $deleteRoute
                    ])
                @endforeach
            </div>
        @endif

        {{-- Paginador --}}
        <div class="mt-3">
            {{ $items->appends(request()->query())->links() }}
        </div>
    @else
        {{-- Mensaje cuando no hay datos --}}
        <p>{{ $emptyMessage ?? 'No hay registros disponibles.' }}</p>
    @endif
</div>
