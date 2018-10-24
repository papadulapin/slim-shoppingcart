<?php

namespace App\Basket;

use App\Basket\Basket;
use App\Basket\Exceptions\QuantityExceededException;
use App\Models\Product;
use App\Support\Storage\Contracts\StorageInterface;

class Basket
{
    protected $storage;
    protected $product;

    public function __construct(StorageInterface $storage, Product $product)
    {
        $this->storage = $storage;
        $this->product = $product;
    }

    public function add(Product $product, $quantity)
    {
        // dd('debug: in basket: add()');
        if ($this->has($product)) {
            //set quantity to the current quantity + new quantity

            $quantity = $this->get($product)['quantity'] + $quantity;
        }

        //update session with product
        $this->update($product, $quantity);

    }

    public function update(Product $product, $quantity)
    {        
        // dd($product);

        if (! $this->product->find($product->id)->hasStock($quantity)) {
            throw new QuantityExceededException;
        }

        if ($quantity == 0) {
            $this->remove($product);
            return;
        }

        $this->storage->set($product->id, [
            'product_id' => (int) $product->id,
            'quantity' => (int) $quantity
        ]);

        // dd($_SESSION);
    }

    public function remove(Product $product)
    {
        $this->storage->unset($product->id);
    }

    public function has(Product $product)
    {
        return $this->storage->exists($product->id);
    }

    public function get(Product $product)
    {
        return $this->storage->get($product->id);
    }

    public function clear()
    {
        $this->storage->clear();
    }

    public function all()
    {
        $ids = [];
        $items = [];

        foreach ($this->storage->all() as $product) {
            $ids[] = $product['product_id'];
        }

        $products = $this->product->find($ids);
        // dump($products->count());
        // dd($ids);

        foreach ($products as $product) {
            $product->quantities = $this->get($product)['quantity'];

            // dump($product);

            $items[] = $product; 
        }

        // dump($items);
        return $items;
    }

    public function itemCount()
    {
        return count($this->storage);
    }

    public function subTotal()
    {
        $total = 0;

        foreach ($this->all() as $item) {
            if ($item->outOfStock()) {
                continue;
            }

            $total = $total + $item->price * $item->quantities;
        }

        return $total;
    }

    public function refresh()
    {
        foreach ($this->all() as $item) {
            if (!$item->hasStock($item->quantities)) {
                $this->update($item, $item->stock);
            }
        }
    }
}