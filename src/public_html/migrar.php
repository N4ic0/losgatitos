<?php

$secret = 'gatitos2026';

if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    http_response_code(404);
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<pre>";
echo "Corriendo migraciones...\n\n";

Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();

echo "\nGenerando key...\n";
Artisan::call('key:generate', ['--force' => true, '--show' => true]);
echo Artisan::output();

echo "\nCache limpio...\n";
Artisan::call('optimize:clear');
echo Artisan::output();

echo "\n✅ Completado!</pre>";
