<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        return view('products/index', [
            'title'    => 'Products',
            'active'   => 'products',
            'products' => (new ProductModel())->orderBy('name')->findAll(),
        ]);
    }
}
