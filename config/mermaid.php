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

    'theme' => 'default',

    'themeVariables' => [
        'primaryColor' => '#BB2528',
    ],

    'flowchart' => [
        'useMaxWidth' => false,
        'htmlLabels' => true,
    ],

    'sequence' => [
        'showSequenceNumbers' => true,
    ],

    'gantt' => [
        'axisFormat' => '%m/%d/%Y',
    ],

    'class' => [
        'defaultRenderer' => 'dagre',
    ],

    'git' => [
        'showBranches' => true,
        'showCommitLabel' => true,
    ],
];




