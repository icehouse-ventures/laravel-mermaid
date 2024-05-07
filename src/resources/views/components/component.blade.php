<div class="mermaid {{ $class ?? '' }}">
    {{ isset($data) ? $data : $slot }}
</div>

@once
    
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

    <script>
        mermaid.initialize({
            startOnLoad: true
        });
    </script>

@endonce
