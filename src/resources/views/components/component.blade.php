<div class="mermaid {{ $class ?? '' }}">
    @if(isset($data))
    {{ $data }}
    @else
        {{ \IcehouseVentures\LaravelMermaid\Support\Builder::setTheme($theme ?? '') }}
        {{ $slot }}
    @endif
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js" defer></script>

    <script>
      function initMermaid() {
        if (typeof mermaid === 'undefined') {
          setTimeout(initMermaid, 100);
          return;
        }

        mermaid.initialize({ startOnLoad: true });
        mermaid.contentLoaded();
      }

      document.addEventListener('DOMContentLoaded', initMermaid);
      document.addEventListener('livewire:navigated', initMermaid);
    </script>
@endonce