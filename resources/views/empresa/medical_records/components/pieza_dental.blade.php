@php
    $pieza = $pieza ?? 11;
    $tipo = $tipo ?? 'permanente';
    $posicion = $posicion ?? 'superior';
    $readonly = $readonly ?? false;
@endphp

<div class="diente-card" title="Pieza dental FDI {{ $pieza }}">
    {{-- Número de diente arriba si es superior --}}
    @if($posicion === 'superior')
        <span class="diente-num">{{ $pieza }}</span>
    @endif

    <div class="diente-box" id="{{ $containerId }}_pieza_{{ $pieza }}">
        <svg class="diente-svg" viewBox="0 0 100 100">
            {{-- Cara Superior (Vestibular en superior, Palatina/Lingual en inferior) --}}
            <polygon class="diente-cara" data-cara="v" points="0,0 100,0 75,25 25,25" onclick="clickCaraDental('{{ $containerId }}', '{{ $pieza }}', 'v', this)" />
            
            {{-- Cara Izquierda (Mesial / Distal según cuadrante) --}}
            <polygon class="diente-cara" data-cara="m" points="0,0 25,25 25,75 0,100" onclick="clickCaraDental('{{ $containerId }}', '{{ $pieza }}', 'm', this)" />
            
            {{-- Cara Central (Oclusal / Incisal) --}}
            <polygon class="diente-cara" data-cara="c" points="25,25 75,25 75,75 25,75" onclick="clickCaraDental('{{ $containerId }}', '{{ $pieza }}', 'c', this)" />
            
            {{-- Cara Derecha (Distal / Mesial) --}}
            <polygon class="diente-cara" data-cara="d" points="100,0 100,100 75,75 75,25" onclick="clickCaraDental('{{ $containerId }}', '{{ $pieza }}', 'd', this)" />
            
            {{-- Cara Inferior (Palatino/Lingual en superior, Vestibular en inferior) --}}
            <polygon class="diente-cara" data-cara="p" points="25,75 75,75 100,100 0,100" onclick="clickCaraDental('{{ $containerId }}', '{{ $pieza }}', 'p', this)" />
        </svg>
    </div>

    {{-- Número de diente abajo si es inferior --}}
    @if($posicion === 'inferior')
        <span class="diente-num mt-1">{{ $pieza }}</span>
    @endif
</div>
