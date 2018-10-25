<?php

namespace App\Handlers;

use App\Handlers\Contracts\HandlerInterface;

class RecordFailedPayment implements HandlerInterface
{
    public function handle($event)
    {
        // dump('record failed payment');
        $event->order->payment()->create([
            'failed' => true
        ]);
    }
}