<?php

use App\Http\Controllers\UnitController;
Route::get('/units', [UnitController::class, 'index'])->name('units.index');
Route::get('/units/store', [UnitController::class, 'storeAjax']);
Route::get('/units/{user}/update', [UnitController::class, 'updateAjax']);
Route::get('/units/{unit}/delete', [UnitController::class, 'destroyAjax']);
