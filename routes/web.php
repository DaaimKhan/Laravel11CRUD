<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// 1 way to give the route
// Route::get('/products',[ProductController::class, 'index'])->name('products.index');
// Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
// Route::post('/products',[ProductController::class, 'store'])->name('products.store');
// Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
// Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
// Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.destroy');

// 2 way
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/create', 'create')->name('products.create');
    Route::post('/products', 'store')->name('products.store');
    Route::get('/products/{product}/edit', 'edit')->name('products.edit');
    Route::put('/products/{product}', 'update')->name('products.update');
    Route::delete('/products/{product}', 'destroy')->name('products.destroy');
});

// 3 way
// Route::resource('products', ProductController::class);
