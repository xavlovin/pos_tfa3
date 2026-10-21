<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $customers = new CustomerModel();
        $users     = new UserModel();
        $products  = new ProductModel();

        return view('dashboard/index', [
            'title'           => 'Dashboard',
            'active'          => 'dashboard',
            'customerCount'   => $customers->countAllResults(),
            'userCount'       => $users->countAllResults(),
            'productCount'    => $products->countAllResults(),
            'lowStockCount'   => $products->where('stock <', 10)->countAllResults(),
            'recentCustomers' => $customers->orderBy('id', 'DESC')->findAll(5),
            'recentUsers'     => $users->orderBy('id', 'DESC')->findAll(5),
        ]);
    }
}
