<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class productController extends Controller
{
        public function submit_product(Request $request){
        $product = new product;
        $product->name = $request->product_name;
        $product->desc = $request->product_des;
        $product->price = $request->product_price;
        $product->save();
        return redirect()->back();
    }
}
