<?php

namespace App\Controllers;

use App\Basket\Basket;
use App\Models\Product;
use App\Validation\Contracts\ValidatorInterface;
use App\Validation\Forms\OrderForm;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Router;
use Slim\Views\Twig;


class OrderController
{
    protected $basket;
    protected $router;
    protected $validator;

    public function __construct(Basket $basket, Router $router, ValidatorInterface $validator)
    {
        $this->basket = $basket;
        $this->router = $router;
        $this->validator = $validator;
    }

    public function index(Request $request, Response $response, Twig $view)
    {        
        $this->basket->refresh();

        if (!$this->basket->subTotal()) {
            return $response->withRedirect($this->router->pathFor('cart.index'));
        }

        return $view->render($response, 'order/index.twig');
    }

    public function create(Request $request, Response $response)
    {
        $this->basket->refresh();

        if (!$this->basket->subTotal()) {
            return $response->withRedirect($this->router->pathFor('cart.index'));
        }

        //validate
        $validation = $this->validator->validate($request, OrderForm::rules());

        if ($validation->fails()) {

            return $response->withRedirect($this->router->pathFor('order.index'));
        }

        dd('debug: create()');
    }
}