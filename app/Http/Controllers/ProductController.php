<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Product;


class ProductController extends Controller
{
    // this method will show products page
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('products.list',[
            'products'=> $products
        ]);
    }

    // this method will show create product page
    public function create()
    {
        return view('products.create');
    }

    // this method will store a product in db
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:5',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        if ($request->image != '') {
            $rules['image'] = 'image';
        }

        // here we will insert the product in db
        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        // here we will store image
        if ($request->image != '') {

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext; // Unique image name

            // save image to products directory
            $image->move(public_path('uploads/products'), $imageName);

            // save image name in database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    // this method will edit product page
    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('products.edit',[
            'product'=> $product
        ]);
    }

    // this method will update a product
    public function update($id, Request $request) {
        $product = Product::findOrFail($id);
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:5',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('products.edit', $product->id)->withInput()->withErrors($validator);
        }

        if ($request->image != '') {
            $rules['image'] = 'image';
        }

        // here we will update the product in db
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        // here we will store image
        if ($request->image != '') {
            // delete old image
            File::delete(public_path('uploads/products/'. $product->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext; // Unique image name

            // save image to products directory
            $image->move(public_path('uploads/products'), $imageName);

            // save image name in database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // this method will delete product
    public function destroy($id) {
        $product = Product::findOrFail($id);

        // delete image
        File::delete(public_path('uploads/products/'. $product->image));

        // delete product from database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');

    }
}
