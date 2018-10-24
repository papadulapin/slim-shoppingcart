<?php

namespace App;

use App\Models\Product;
use function DI\get;
use App\Support\Storage\SessionStorage;
use DI\Bridge\Slim\App as DiBridge;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Basket\Basket;
use App\Support\Storage\Contracts\StorageInterface;
use App\Validation\Validator;
use App\Validation\Contracts\ValidatorInterface;

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