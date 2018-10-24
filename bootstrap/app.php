<?php

use App\App;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;

$container = $app->getContainer();

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

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));

$app->add(new \App\Middleware\OldInputMiddleware($container->get(Twig::class)));