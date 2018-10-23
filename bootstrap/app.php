<?php

use App\App;
use Illuminate\Database\Capsule\Manager as Capsule;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'shoppingcart',
    'username' => 'homestead',
    'password' => 'secret',
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

require __DIR__ . '/../app/routes.php';
