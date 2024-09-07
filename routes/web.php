<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\gerenteController;
use App\Http\Controllers\PendenciaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\usuarioComumController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/usuariosComuns', [usuarioComumController::class, 'index']);
    Route::get('/criarUsuarioComum', [usuarioComumController::class, 'create'])->can('createUsuarioComum', User::class);
    Route::post('/criarUsuarioComum', [usuarioComumController::class, 'store']);
    Route::get('/verUsuarioComum/{userId}', [usuarioComumController::class, 'show'])->can('acaoUsuarioComum', [User::class, 'userId']);
    Route::get('/editarUsuarioComum/{userId}', [usuarioComumController::class, 'edit'])->can('acaoUsuarioComum', [User::class, 'userId']);
    Route::get('/gerentes', [gerenteController::class, 'index'])->can('paginaGerente', User::class);
    Route::get('/criarGerente', [gerenteController::class, 'create'])->can('createGerente', User::class);
    Route::get('/verGerente/{gerenteId}', [gerenteController::class, 'show'])->can('acaoGerente', [User::class, 'gerenteId']);
    Route::post('/criarGerente', [gerenteController::class, 'store']);
    Route::get('/administradores', [adminController::class, 'index'])->can('paginaAdministrador', User::class);
    Route::get('/verAdministrador/{admId}', [adminController::class, 'show'])->can('acaoAdministrador', [User::class, 'admId']);
    Route::get('/criarAdministrador', [adminController::class, 'create'])->can('createAdministrador', User::class);
    Route::post('/criarAdministrador', [adminController::class, 'store']);
    
    Route::post('/editarUsuarioComum', [usuarioComumController::class, 'update']);
    Route::get('/editarGerente/{gerenteId}', [gerenteController::class, 'edit'])->can('acaoGerente', [User::class, 'gerenteId']);
    Route::post('/editarGerente', [gerenteController::class, 'update']);
    Route::get('/editarAdministrador/{admId}', [adminController::class, 'edit'])->can('acaoAdministrador', [User::class, 'admId']);
    Route::post('/editarAdministrador', [adminController::class, 'update']);
    Route::post('/excluirUsuarioComum', [usuarioComumController::class, 'destroy']);
    Route::post('/excluirGerente', [gerenteController::class, 'destroy']);
    Route::post('/excluirAdministrador', [adminController::class, 'destroy']);
    Route::get('/saque_deposito', [TransacaoController::class, 'saque_deposito']);
    Route::post('/saque_deposito', [TransacaoController::class, 'sacar_ou_depositar']);
    Route::get('/transferencia', [TransacaoController::class, 'transferencia']);
    Route::post('/transferencia', [TransacaoController::class, 'requerirTransferencia']);
    Route::get('/pendencias', [PendenciaController::class, 'index']);
    Route::post('/pendencias', [PendenciaController::class, 'acao']);
    Route::get('/emprestimo', [EmprestimoController::class, 'index']);
    Route::post('/emprestimo', [EmprestimoController::class, 'solicitacao']);
    Route::get('/extrato', [TransacaoController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
