<?php

use App\Http\Controllers\CustomerController;
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
Route::put('/customers/{id}', [CustomerController::class,'update']);    Route::get('/customers/edit/{id}', [CustomerController::class,'edit']);
Route::get('/customers/next-code/{typeId}', [CustomerController::class,'nextCode']);
Route::get('/customers/export/excel', [CustomerController::class,'exportExcel'])->name('customers.export.excel');
Route::get('/customers/export/pdf', [CustomerController::class,'exportPDF'])->name('customers.export.pdf');
Route::get('/get-townships/{division_id}', [CustomerController::class, 'getTownships']);
Route::get('/generate-code/{typeId}', function ($typeId) {
    $type = \App\Models\CustomerType::findOrFail($typeId);
    $prefix = strtoupper(substr($type->name, 0, 1));
    $latest = \App\Models\Customer::where('customer_code', 'like', "$prefix%")->latest('id')->first();
    $next = $latest ? ((int) substr($latest->customer_code, 1)) + 1 : 1;
    $code = $prefix . str_pad($next, 8, '0', STR_PAD_LEFT);
    return response()->json(['code' => $code]);
});