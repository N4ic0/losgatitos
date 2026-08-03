<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$session = $app['session']->driver();
$session->start();

$user = App\Models\User::query()->where('email', 'admin@motellosgatitos.cl')->first();

$request = Illuminate\Http\Request::create('/admin/dashboard/habitacion/1/aire', 'POST', ['aire' => true]);
$request->headers->set('Accept', 'application/json');
$request->headers->set('X-CSRF-TOKEN', $session->token());
$request->setLaravelSession($session);
$request->setUserResolver(fn () => $user);
$app['request'] = $request;
Illuminate\Support\Facades\Auth::setRequest($request);
Illuminate\Support\Facades\Auth::login($user);
$session->save();

$response = $kernel->handle($request);
echo 'STATUS=' . $response->getStatusCode() . PHP_EOL;
echo 'BODY=' . $response->getContent() . PHP_EOL;
echo 'DB=' . App\Models\Habitacion::find(1)->aire . PHP_EOL;