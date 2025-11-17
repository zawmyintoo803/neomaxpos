<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

require __DIR__.'/category.php';
require __DIR__.'/product.php';
require __DIR__.'/paymentmethod.php';
require __DIR__.'/unit.php';
require __DIR__.'/customer.php';
require __DIR__.'/supplier.php';
require __DIR__.'/customertype.php';
require __DIR__.'/suppliertype.php';
require __DIR__.'/user.php';
require __DIR__.'/sales.php';
require __DIR__.'/purchases.php';
require __DIR__.'/role.php';
