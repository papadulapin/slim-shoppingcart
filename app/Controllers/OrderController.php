<?php

namespace App\Controllers;

use App\Basket\Basket;
use App\Models\Address;
use App\Models\Customer;
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

    public function create(Request $request, Response $response, Customer $customer, Address $address)
    {
        $this->basket->refresh();

        dd($request->getParams());

        if (!$this->basket->subTotal()) {
            return $response->withRedirect($this->router->pathFor('cart.index'));
        }

        //validate
        $validation = $this->validator->validate($request, OrderForm::rules());

        if ($validation->fails()) {

            return $response->withRedirect($this->router->pathFor('order.index'));
        }

        //create
        $hash = bin2hex(random_bytes(32));

        $customer = $customer->firstOrCreate([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
        ]);

        $address = $address->firstOrCreate([
            'address1' => $request->getParam('address1'),
            'address2' => $request->getParam('address2'),
            'city' => $request->getParam('city'),
            'postal_code' => $request->getParam('postal_code'),
        ]);

        $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $this->basket->subTotal() + 5,
            'address_id' => $address->id,

        ]);

        $allItems = $this->basket->all();

        $order->products()->saveMany(
            $this->basket->all(),
            $this->getQuantities($this->basket->all())
        );
    }

    protected function getQuantities($items)
    {
        $quantities = [];

        foreach ($items as $item) {
            $quantities[] = ['quantity' => $item->quantities];
        }

        // dump($quantities);

        return $quantities;
    }
}