<?php

namespace App\Handlers;

use App\Handlers\Contracts\HandlerInterface;

class UpdateStock implements HandlerInterface
{
    public function handle($event)
    {
        // dump('update stock');
        foreach ($event->basket->all() as $product) {
            $product->decrement('stock', $product->quantities);
        }
    }
}