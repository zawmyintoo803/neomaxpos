<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
Route::group(['prefix'=>'user'],function(){
    Route::get('login',[LoginController::class,'loginPage'])->name('login');
    Route::post('login',[LoginController::class,'login'])->name('user#login');
    Route::get('register',[LoginController::class,'registerPage'])->name('register#page');
    Route::post('register',[RegisteredUserController::class,'store'])->name('register');
});