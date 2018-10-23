<?php

namespace App\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use App\Models\Product;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProductController
{
    public function get($slug, Request $request, Response $response, Twig $view, Router $router, Product $product)
    {
        $product = $product->where('slug', $slug)->first();

        if (! $product) {
            return $response->withRedirect($router->pathFor('home'));
        }

        return $view->render($response, 'products/product.twig', [
            'product' => $product
        ]);
    }

}