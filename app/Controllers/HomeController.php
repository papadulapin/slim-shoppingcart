<?php

namespace App\Controllers;

use App\Models\Product;
use Slim\Views\Twig;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    public function index(Request $request, Response $response, Twig $view, Product $product)
    {        
        $products = $product->get();

        // dd($products);
        return $view->render($response, 'home.twig', [
            'products' => $products
        ]);
    }
}