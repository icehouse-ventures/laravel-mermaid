<div class="mermaid {{ $class ?? '' }}">
    @if(isset($data))
    {{ $data }}
    @else
        {{ \IcehouseVentures\LaravelMermaid\Support\Builder::setTheme($theme ?? '') }}
        {{ $slot }}
    @endif
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

    <script>
        mermaid.initialize({
            startOnLoad: true
        });
    </script>
@endonce