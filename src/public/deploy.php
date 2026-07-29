<?php
// Temporal: ejecuta artisan commands via web (borrar después de usar)
$_SERVER['REQUEST_METHOD'] = 'GET';

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [];

// 1. Migraciones pendientes
$exitCode = \Artisan::call('migrate', ['--force' => true]);
$commands[] = 'migrate --force: ' . ($exitCode === 0 ? 'OK' : 'FAILED') . "\n" . \Artisan::output();

// 2. Limpiar cache
\Artisan::call('optimize:clear');
$commands[] = 'optimize:clear: ' . \Artisan::output();

echo '<pre>' . implode("\n\n", $commands) . '</pre>';
