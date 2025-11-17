<?php

use App\Http\Controllers\CategoryController;
Route::get('/category', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/category', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::post('/category/{id}/update', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
