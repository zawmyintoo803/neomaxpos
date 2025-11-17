<?php
Route::get('/cashier', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/store', [SaleController::class, 'storeAjax']);
Route::get('/sales/{sale}/update', [SaleController::class, 'updateAjax']);
Route::get('/sales/{sale}/delete', [SaleController::class, 'destroyAjax']);

