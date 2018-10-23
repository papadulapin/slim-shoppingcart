<?php

namespace App;

use App\Models\Product;
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

                return $twig;
            },


            Product::class => function (ContainerInterface $c) {
                return new Product;
            },

        ];

        $builder->addDefinitions($definitions);


    }
}