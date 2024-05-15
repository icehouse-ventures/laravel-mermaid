<div x-data="mermaid">
    <div wire:ignore x-ref="target" class="{{ $class ?? '' }}"></div>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
@endassets

@script
<script>
    Alpine.data('mermaid', () => ({
        init() {
            mermaid.initialize({
                startOnLoad: false,
            });

            this.$watch('$wire.{{ $attributes->wire('model')->value() }}', () => {
                this.render();
            });

            this.render();
        },
        async render() {
            const { svg } = await mermaid.render('graph-{{ $this->id() }}', this.$wire.{{ $attributes->wire('model')->value() }});
            this.$refs.target.innerHTML = svg;
        }
    }));
</script>
@endscript
