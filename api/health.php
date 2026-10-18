<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$requiredExtensions = [
    'curl',
    'dom',
    'mbstring',
    'openssl',
    'pdo_pgsql',
];

$missingExtensions = array_values(
    array_filter(
        $requiredExtensions,
        fn (string $extension): bool => !extension_loaded($extension)
    )
);

$healthy = count($missingExtensions) === 0;

http_response_code($healthy ? 200 : 503);

echo json_encode(
    [
        'status' => $healthy ? 'ok' : 'error',
        'runtime' => 'php',
        'php_version' => PHP_VERSION,
        'extensions' => [
            'required' => $requiredExtensions,
            'missing' => $missingExtensions,
        ],
    ],
    JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
);
