<?php
Route::get('/customer-types', [CustomerTypeController::class, 'index'])->name('customer-types.index');
Route::post('/customer-types', [CustomerTypeController::class, 'store']);
Route::put('/customer-types/{id}', [CustomerTypeController::class, 'update']);
Route::delete('/customer-types/{id}', [CustomerTypeController::class, 'destroy']);
Route::get('/customer-types/export-excel', [CustomerTypeController::class, 'exportExcel']);
Route::get('/customer-types/export-pdf', [CustomerTypeController::class, 'exportPDF']);
