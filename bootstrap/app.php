<?php

use App\App;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;
use Braintree_Gateway;

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

/*$gateway = new Braintree_Gateway([
  'environment' => 'sandbox',
  'merchantId' => 'cwhkd8hrrd9gnxn8',
  'publicKey' => 'x5kks827985vxj26',
  'privateKey' => '31dbe14839960345fbd3e7b287dc80d9'
]);*/

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('cwhkd8hrrd9gnxn8');
Braintree_Configuration::publicKey('x5kks827985vxj26');
Braintree_Configuration::privateKey('31dbe14839960345fbd3e7b287dc80d9');

require __DIR__ . '/../app/routes.php';

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));

$app->add(new \App\Middleware\OldInputMiddleware($container->get(Twig::class)));