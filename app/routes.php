<?php

$app->get('/', ['App\Controllers\HomeController', 'index'])->setName('home');

$app->get('/products/{slug}', ['App\Controllers\ProductController', 'get'])->setName('product.get');