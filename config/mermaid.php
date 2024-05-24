<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Mermaid Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the default settings for Mermaid.js diagrams.
    | See https://mermaid-js.github.io/mermaid/#/Setup for available options.
    |
    */

    // Mermaid theme to use for the diagram.
    // First Party Mermaid themes: 'default', 'forest', 'dark', 'neutral', 'base'.
    // To a custom theme file in the themes directory set to 'custom'.
    'theme' => 'custom',

    // Custom theme file to use for the diagram.
    // Must be a valid JSON file in the themes directory.
    // Laravel Mermaid Package comes with 'bootstrap', 'tailwind', 'darkmode',
    'themeFile' => 'tailwind',
];
