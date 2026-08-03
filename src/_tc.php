<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Habitacion;
use Illuminate\Http\Request;

$habitacion = Habitacion::find(1);
echo 'antes=' . var_export($habitacion->aire, true) . PHP_EOL;

$ctrl = app(App\Http\Controllers\DashboardController::class);
$req = Request::create('/admin/dashboard/habitacion/1/aire', 'POST', ['aire' => true]);
$req->headers->set('Accept', 'application/json');

$resp = $ctrl->toggleAire($req, $habitacion);
echo 'STATUS=' . $resp->getStatusCode() . PHP_EOL;
echo 'BODY=' . $resp->getContent() . PHP_EOL;
echo 'DB=' . var_export(Habitacion::find(1)->aire, true) . PHP_EOL;
Habitacion::find(1)->update(['aire' => false]);