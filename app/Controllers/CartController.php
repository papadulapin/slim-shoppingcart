<?php

namespace App\Controllers;

use App\Basket\Basket;
use App\Basket\Exceptions\QuantityExceededException;
use App\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Router;
use Slim\Views\Twig;

class CartController
{
    protected $basket;
    protected $product;

    public function __construct(Basket $basket, Product $product)
    {
        $this->basket = $basket;
        $this->product = $product;
    }
    public function index(Request $request, Response $response, Twig $view)
    {
        return $view->render($response, 'cart/index.twig');
    }

    public function add($slug, $quantity, Request $request, Response $response, Router $router)
    {
        
        $product = $this->product->where('slug', $slug)->first();
        // dd($product);

        if (!$product) {
            return $response->withRedirect($router->pathFor('home'));
        }

        try {            
            // dump($product->title);
            // dump($quantity);
            $this->basket->add($product, $quantity);
        } catch (QuantityExceededException $e) {

        }


        // dd($_SESSION);
        
        return $response->withRedirect($router->pathFor('cart.index'));
    }
}