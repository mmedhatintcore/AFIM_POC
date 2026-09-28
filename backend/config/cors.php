<?php

/*
 * Browsers may only call the API from these origins. FRONTEND_URL may hold
 * one origin or a comma-separated list and is added on top of the built-in
 * list, so a missing/mis-set env var can no longer lock the website out.
 */
$fromEnv = array_filter(array_map(
    fn (string $origin) => rtrim(trim($origin), '/'),
    explode(',', (string) env('FRONTEND_URL', '')),
));

$builtIn = [
    'https://afim.intcore.dev',
    'https://afim.com.eg',
    'https://www.afim.com.eg',
];

// Local development only — never advertised by a production deployment.
if (env('APP_ENV', 'production') !== 'production') {
    $builtIn[] = 'http://localhost:3000';
}

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_values(array_unique([...$builtIn, ...$fromEnv])),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 3600,
    'supports_credentials' => false,
];
