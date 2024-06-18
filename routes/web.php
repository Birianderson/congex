<?php

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
