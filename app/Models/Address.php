<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}