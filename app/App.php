<?php

namespace App;

use App\Basket\Basket;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Support\Storage\Contracts\StorageInterface;
use App\Support\Storage\SessionStorage;
use App\Validation\Contracts\ValidatorInterface;
use App\Validation\Validator;
use DI\Bridge\Slim\App as DiBridge;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use function DI\get;

class App extends DiBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . '/container.php');


        $definitions = [

            Twig::class => function (ContainerInterface $c) {
                $twig = new Twig(__DIR__ . '/../resources/views', [
                    'cache' => false
                ]);

                $twig->addExtension(new TwigExtension(
                    $c->get('router'),
                    $c->get('request')->getUri()
                ));

                $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));
                
                return $twig;
            },


            Product::class => function (ContainerInterface $c) {
                return new Product;
            },

            Order::class => function (ContainerInterface $c) {
                return new Order;
            },

            Customer::class => function (ContainerInterface $c) {
                return new Customer;
            },



            Address::class => function (ContainerInterface $c) {
                return new Address;
            },

            ValidatorInterface::class => function (ContainerInterface $c) {
                return new Validator;
            },

            StorageInterface::class => function (ContainerInterface $c) {
                return new SessionStorage('cart');
            },


            Basket::class => function (ContainerInterface $c) {
                return new Basket(
                    $c->get(SessionStorage::class),
                    $c->get(Product::class)
                );
            },

        ];

        $builder->addDefinitions($definitions);


    }
}