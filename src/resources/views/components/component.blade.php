<div class="mermaid {{ $class ?? '' }}">
    @if(isset($data))
        {{ $data }}
    @else
        {{ \IcehouseVentures\LaravelMermaid\Support\Builder::setTheme($theme ?? '') }}
        {{ $slot }}
    @endif
</div>

@once
    <script src="{{ config('mermaid.cdn') }}" defer></script>

    <script>
      async function initLaravelMermaid() {
        if (typeof mermaid === 'undefined') {
          window.setTimeout(initLaravelMermaid, 100);
          return
        }

        if (!window.laravelMermaidInitialized) {
          mermaid.initialize({
            startOnLoad: false,
            securityLevel: @js(config('mermaid.securityLevel', 'strict')),
          })
          window.laravelMermaidInitialized = true
        }

        await mermaid.run({
          querySelector: '.mermaid:not([data-processed="true"])',
        })
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLaravelMermaid)
      } else {
        initLaravelMermaid()
      }

      document.addEventListener('livewire:navigated', initLaravelMermaid)
    </script>
@endonce
