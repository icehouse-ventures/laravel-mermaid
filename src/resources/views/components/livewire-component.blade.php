<div x-data="mermaid">
    <div wire:ignore x-ref="target" class="{{ $class ?? '' }}"></div>
</div>

@assets
    <script src="{{ config('mermaid.cdn') }}"></script>
@endassets

@script
<script>
    Alpine.data('mermaid', () => ({
        init() {
            mermaid.initialize({
                startOnLoad: false,
                securityLevel: @js(config('mermaid.securityLevel', 'strict')),
            })

            this.$watch('$wire.{{ $attributes->wire('model')->value() }}', () => {
                this.render()
            })

            this.render()
        },
        async render() {
            const { svg } = await mermaid.render(
                'graph-{{ $this->id() }}',
                this.$wire.{{ $attributes->wire('model')->value() }}
            )
            this.$refs.target.innerHTML = svg
        }
    }))
</script>
@endscript
