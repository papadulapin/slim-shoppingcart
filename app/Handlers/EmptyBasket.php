<?php

namespace App\Handlers;

use App\Handlers\Contracts\HandlerInterface;

class EmptyBasket implements HandlerInterface
{
    public function handle($event)
    {
        // dump('empty basket');
        $event->basket->clear();
    }
}