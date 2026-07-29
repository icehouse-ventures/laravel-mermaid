<?php

use IcehouseVentures\LaravelMermaid\ServiceProvider;
use IcehouseVentures\LaravelMermaid\Support\Builder;

test('it registers the builder bindings', function () {
    expect(app('mermaid'))
        ->toBeInstanceOf(Builder::class)
        ->and(app('mermaid-facade'))
        ->toBeInstanceOf(Builder::class);
});

test('it publishes configuration', function () {
    $this->artisan('vendor:publish', [
        '--provider' => ServiceProvider::class,
        '--tag' => 'config',
        '--force' => true,
    ]);

    $this->assertFileExists(config_path('mermaid.php'));
});

test('it renders the major-pinned Mermaid runtime and current run API', function () {
    $html = $this->blade(
        '<x-mermaid::component :data="$data" class="diagram" />',
        ['data' => "flowchart LR;\nA-->B;"]
    );

    expect((string) $html)
        ->toContain('class="mermaid diagram"')
        ->toContain('mermaid@11/dist/mermaid.min.js')
        ->toContain('mermaid.run')
        ->toContain('securityLevel');
});
