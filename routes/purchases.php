<?php
Route::get('/purchases',[PurchaseController::class,'index'])->name('purchases.index');
Route::get('/purchases/create',[PurchaseController::class,'create'])->name('purchases.create');
Route::post('/purchases',[PurchaseController::class,'store'])->name('purchases.store');
Route::get('/purchases/export/excel',[PurchaseController::class,'exportExcel'])->name('purchases.export.excel');
Route::get('/purchases/export/pdf',[PurchaseController::class,'exportPDF'])->name('purchases.export.pdf');
