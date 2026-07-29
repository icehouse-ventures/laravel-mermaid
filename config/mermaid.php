<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mermaid Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the default settings for Mermaid.js diagrams.
    | See https://mermaid.js.org/config/configuration.html for available options.
    |
    */

    // Mermaid theme to use for the diagram.
    // First Party Mermaid themes: 'default', 'forest', 'dark', 'neutral', 'base'.
    // To use a custom theme file from the themes directory, set this to 'custom'.
    'theme' => 'custom',

    // Custom theme file to use for the diagram.
    // Must be a valid JSON file in the themes directory.
    // Laravel Mermaid includes 'bootstrap', 'tailwind', and 'darkmode'.
    'themeFile' => 'tailwind',

    // Pin Mermaid to a major version to accept compatible fixes without
    // unexpectedly upgrading applications to a future breaking major.
    'cdn' => 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.min.js',

    // Mermaid security level. Use 'strict' unless diagrams contain trusted,
    // application-authored HTML labels that require a looser setting.
    'securityLevel' => 'strict',
];
