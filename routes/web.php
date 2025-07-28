<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class,'index'])->name('products.index');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/fetch', [ProductController::class, 'fetch'])->name('products.fetch');
Route::put('/products/{product}/update', [ProductController::class, 'update'])->name('products.update');
