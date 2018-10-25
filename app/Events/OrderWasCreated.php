<?php

namespace App\Events;

use App\Basket\Basket;
use App\Events\Event;
use App\Models\Order;

class OrderWasCreated extends Event
{
    public $order;
    public $basket;

    public function __construct(Order $order, Basket $basket)
    {
        $this->order = $order;
        $this->basket = $basket;
    }
}