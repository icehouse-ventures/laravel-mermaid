<div class="mermaid">
    {{ $slot }}
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
        <script>
            mermaid.initialize({
                startOnLoad: true
            });
        </script>
    @endpush
@endonce