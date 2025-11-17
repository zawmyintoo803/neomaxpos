<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// Customer Product Page
Route::get('/customers', [ProductController::class, 'index'])->name('home');

// Admin Product Page (CRUD)
Route::prefix('admin')->group(function(){
    Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])
    ->name('admin.products.destroy');
    Route::get('admin/products/ajax-search', [AdminProductController::class, 'ajaxSearch'])->name('admin.products.ajax-search');


});
