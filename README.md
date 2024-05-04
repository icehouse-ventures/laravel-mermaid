# laravel-mermaid
Simple Mermaid diagrams for Laravel applications. Laravel Mermaid is a package that allows you to easily generate Mermaid diagrams in your Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require icehouse-ventures/laravel-mermaid
```

## Blade Component 
The package provides a Blade component that you can use to generate Mermaid diagrams in your views. Here is an example of how you can use the Blade component to generate a simple flowchart diagram:

<x-mermaid::mermaid>
    graph TD;
        A-->B;
        A-->C;
        B-->D;
        C-->D;
</x-mermaid::mermaid>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
