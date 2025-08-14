<table class="table table-bordered">
    <thead>
        <tr>
            @foreach($tableHeaders as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                @foreach($tableFields as $field)
                    <td>{{ $item->{$field} }}</td>
                @endforeach
                <td>
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
