<?php

use App\Http\Controllers\Contrato\ContratoController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Fiscal\FiscalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'contrato', 'namespace' => 'App\Http\Controllers\ContratoController'], function() {
    Route::get('/', [ContratoController::class, 'index'])->name('contrato.index');
    Route::get('/add', [ContratoController::class, 'add'])->name('contrato.add');
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
    Route::get('/historico/{id}',[EmpresaController::class, 'historico'])->name('empresa.historico');
    Route::get('/{id}',[EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::post('/{id}',[EmpresaController::class, 'update'])->name('empresa.update');
    Route::delete('/delete/{id}',[EmpresaController::class, 'delete'])->name('empresa.delete');
});

Route::group(['prefix' => 'fiscal', 'namespace' => 'App\Http\Controllers\FiscalController'], function() {
    Route::get('/', [FiscalController::class, 'index'])->name('fiscal.index');
    Route::get('/add', [FiscalController::class, 'add'])->name('fiscal.add');
    Route::get('/list', [FiscalController::class, 'list'])->name('fiscal.list');
    Route::post('/',[FiscalController::class,'create'])->name('fiscal.create');
    Route::get('/historico/{id}',[FiscalController::class, 'historico'])->name('fiscal.historico');
    Route::get('/{id}',[FiscalController::class, 'edit'])->name('fiscal.edit');
    Route::post('/{id}',[FiscalController::class, 'update'])->name('fiscal.update');
    Route::delete('/delete/{id}',[FiscalController::class, 'delete'])->name('fiscal.delete');
});
