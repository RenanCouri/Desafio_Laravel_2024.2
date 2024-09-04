<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\gerenteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\usuarioComumController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/usuariosComuns',[usuarioComumController::class,'index']);
Route::get('/criarUsuarioComum',[usuarioComumController::class,'create']);
Route::post('/criarUsuarioComum',[usuarioComumController::class,'store']);
Route::get('/verUsuarioComum/{userId}',[usuarioComumController::class,'show']);
Route::get('/gerentes',[gerenteController::class,'index']);
Route::get('/criarGerente',[gerenteController::class,'create']);
Route::get('/verGerente/{gerenteId}',[gerenteController::class,'show']);
Route::post('/criarGerente',[gerenteController::class,'store']);
Route::get('/administradores',[adminController::class,'index']);
Route::get('/verAdministrador/{admId}',[adminController::class,'show']);
Route::get('/criarAdministrador',[adminController::class,'create']);
Route::post('/criarAdministrador',[adminController::class,'store']);
Route::get('/editarUsuarioComum/{userId}',[usuarioComumController::class,'edit']);
Route::post('/editarUsuarioComum',[usuarioComumController::class,'update']);
Route::get('/editarGerente/{gerenteId}',[gerenteController::class,'edit']);
Route::post('/editarGerente',[gerenteController::class,'update']);
Route::get('/editarAdministrador/{admId}',[adminController::class,'edit']);
Route::post('/editarAdministrador',[adminController::class,'update']);
Route::post('/excluirUsuarioComum',[usuarioComumController::class,'destroy']);
Route::post('/excluirGerente',[gerenteController::class,'destroy']);
Route::post('/excluirAdministrador',[adminController::class,'destroy']);
Route::get('/saque_deposito',[TransacaoController::class,'saque_deposito']);
Route::post('/saque_deposito',[TransacaoController::class,'sacar_ou_depositar']);
Route::get('/transferencia',[TransacaoController::class,'transferencia']);
Route::post('/transferencia',[TransacaoController::class,'requerirTransferencia']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
