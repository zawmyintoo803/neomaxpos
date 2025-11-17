<?php

Route::get('/roles', [SupplierController::class, 'index'])->name('roles.index');
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update']);
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy']);
Route::get('/get-townships/{divisionId}', [SupplierController::class, 'getTownships']);
