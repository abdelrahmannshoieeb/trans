<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $products = \App\Models\Product::all();
        
       return response()->json([
           'message' => 'Hello World',
           'data' => [
            'products' => $products
           ]
       ]);
    }
}
