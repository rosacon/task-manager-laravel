<div class="col-sm-6 col-md-4 col-lg-3 d-flex mb-3">
    <x-card
        class="w-100"
        :title="$item->{$titleField ?? 'name'}"
        :link="route($showRoute, $item)"
        :image="$item->{$imageField ?? 'image'} ?? asset('images/default.png')"
    >
        {{-- Campos extra (opcional) --}}
        @if(!empty($extraFields))
            @foreach($extraFields as $fieldLabel => $fieldName)
                <p class="mb-1">
                    <strong>{{ $fieldLabel }}:</strong>
                    {{ $item->{$fieldName} ?? '' }}
                </p>
            @endforeach
        @endif

        {{-- Acciones --}}
        <div class="mt-3">
            @can('update', $item)
                <a href="{{ route($editRoute, $item) }}" class="btn btn-sm btn-warning me-1">Editar</a>
            @endcan
            @can('delete', $item)
                <form action="{{ route($deleteRoute, $item) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">
                        Eliminar
                    </button>
                </form>
            @endcan
        </div>
    </x-card>
</div>
