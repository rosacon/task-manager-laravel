@props(['title' => 'Sin título', 'image' => null, 'link' => null])

<div {{ $attributes->merge(['class' => 'card h-100 shadow-sm mb-3']) }}>
    @php
        $img = $image ?: asset('images/default-task.png');
    @endphp

    <img src="{{ $img }}" class="card-img-top" alt="{{ $title }}">

    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $title }}</h5>
        <div class="card-text mb-3">
            {{ $slot }}
        </div>

        @if($link)
            <a href="{{ $link }}" class="btn btn-primary mt-auto">Ver más</a>
        @endif
    </div>
</div>
