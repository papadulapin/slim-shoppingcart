<?php

namespace App\Handlers;

use App\Events\Event;
use App\Handlers\Contracts\HandlerInterface;

class MarkOrderPaid implements HandlerInterface
{
    public function handle($event)
    {
        // dump('mark order paid');
        $event->order->update([
            'paid' => true
        ]); 
    }
}