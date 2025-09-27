<?php

declare(strict_types=1);

return [
    'preset' => 'laravel', // other options include 'laravel', 'symfony', 'magento2', 'drupal'

    'exclude' => [
        // 'vendor/*',
        // add directories or files you want to exclude from analysis here
    ],

    'add' => [
        // add custom insights here if needed
    ],

    'remove' => [
        // add insights you want to disable here
    ],

    'config' => [
        // customize specific insight options, for example:
        PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
        ],
    ],

    'requirements' => [
        // set the levels for quality, complexity, architecture, and style if desired
        // 'min-quality' => 70,
        // 'min-complexity' => 70,
        // 'min-architecture' => 70,
        // 'min-style' => 70,
        // 'disable-security-check' => false,
    ],
];
