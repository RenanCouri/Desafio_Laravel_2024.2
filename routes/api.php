<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\GerenteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/gerentes',[GerenteController::class,'index']);
Route::get('/administradores',[AdminController::class,'index']);