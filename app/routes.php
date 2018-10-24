<?php

$app->get('/', ['App\Controllers\HomeController', 'index'])->setName('home');

$app->get('/products/{slug}', ['App\Controllers\ProductController', 'get'])->setName('product.get');

$app->get('/cart', ['App\Controllers\CartController', 'index'])->setName('cart.index');

$app->get('/cart/add/{slug}/{quantity}', ['App\Controllers\CartController', 'add'])->setName('cart.add');