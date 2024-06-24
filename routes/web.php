<?php

use App\Http\Controllers\Cargo\CargoController;
use App\Http\Controllers\Contrato\ContratoController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Pessoa\PessoaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'contrato', 'namespace' => 'App\Http\Controllers\ContratoController'], function() {
    Route::get('/', [ContratoController::class, 'index'])->name('contrato.index');
    Route::get('/list', [ContratoController::class, 'list'])->name('contrato.list');
    Route::post('/',[ContratoController::class,'create'])->name('contrato.create');
    Route::get('/historico/{id}',[ContratoController::class, 'historico'])->name('contrato.historico');
    Route::get('/{id}',[ContratoController::class, 'edit'])->name('contrato.edit');
    Route::post('/{id}',[ContratoController::class, 'update'])->name('contrato.update');
    Route::post('/{id}/delete',[ContratoController::class, 'delete'])->name('contrato.delete');
});

Route::group(['prefix' => 'empresa', 'namespace' => 'App\Http\Controllers\EmpresaController'], function() {
    Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::get('/add', [EmpresaController::class, 'add'])->name('empresa.add');
    Route::get('/list', [EmpresaController::class, 'list'])->name('empresa.list');
    Route::post('/',[EmpresaController::class,'create'])->name('empresa.create');
    Route::get('/get-by-query', [EmpresaController::class, 'getByQuery'])->name('legislacao.get-by-query');
    Route::get('/historico/{id}',[EmpresaController::class, 'historico'])->name('empresa.historico');
    Route::get('/{id}',[EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::post('/{id}',[EmpresaController::class, 'update'])->name('empresa.update');
    Route::delete('/delete/{id}',[EmpresaController::class, 'delete'])->name('empresa.delete');
});

Route::group(['prefix' => 'pessoa', 'namespace' => 'App\Http\Controllers\PessoaController'], function() {
    Route::get('/', [PessoaController::class, 'index'])->name('pessoa.index');
    Route::get('/list', [PessoaController::class, 'list'])->name('pessoa.list');
    Route::post('/',[PessoaController::class,'create'])->name('pessoa.create');
    Route::get('/historico/{id}',[PessoaController::class, 'historico'])->name('pessoa.historico');
    Route::get('/{id}',[PessoaController::class, 'edit'])->name('pessoa.edit');
    Route::post('/{id}',[PessoaController::class, 'update'])->name('pessoa.update');
    Route::delete('/delete/{id}',[PessoaController::class, 'delete'])->name('pessoa.delete');
});

Route::group(['prefix' => 'cargo', 'namespace' => 'App\Http\Controllers\CargoController'], function() {
    Route::get('/', [CargoController::class, 'index'])->name('cargo.index');
    Route::get('/add', [CargoController::class, 'add'])->name('cargo.add');
    Route::get('/list', [CargoController::class, 'list'])->name('cargo.list');
    Route::post('/',[CargoController::class,'create'])->name('cargo.create');
    Route::get('/historico/{id}',[CargoController::class, 'historico'])->name('cargo.historico');
    Route::get('/{id}',[CargoController::class, 'edit'])->name('cargo.edit');
    Route::post('/{id}',[CargoController::class, 'update'])->name('cargo.update');
    Route::delete('/delete/{id}',[CargoController::class, 'delete'])->name('cargo.delete');
});
