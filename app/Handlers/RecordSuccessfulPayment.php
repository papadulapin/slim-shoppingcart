<?php

namespace App\Handlers;

use App\Handlers\Contracts\HandlerInterface;

class RecordSuccessfulPayment implements HandlerInterface
{
    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle($event)
    {
        // dump('record Successful payment');        
        $event->order->payment()->create([
            'failed' => false,
            'transaction_id' => $this->transaction->id
        ]);
    }
}