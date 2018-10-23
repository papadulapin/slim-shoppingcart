<?php

namespace App\Controllers;

use App\Models\Product;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HomeController
{
    public function index(Request $request, Response $response, Twig $view, Product $product)
    {        
        $products = $product->get();

        dd($products);
        return $view->render($response, 'home.twig');
    }
}