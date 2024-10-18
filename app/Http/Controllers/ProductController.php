<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;


class ProductController extends Controller
{
    // this method will show products page
    public function index(){
        return view('products.list');
    }

    // this method will show create product page
    public function create(){
        return view('products.create');
    }

    // this method will store a product in db
    public function store(Request $request){
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:5',
            'price'=> 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        // here we will insert the product in db
        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    // this method will edit product page
    public function edit(){

    }

    // this method will update a product
    public function update(){

    }

    // this method will delete product
    public function destroy(){

    }
}
