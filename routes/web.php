<?php

use App\Http\Controllers\Cargo\CargoController;
use App\Http\Controllers\Contrato\ContratoController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Empenho\EmpenhoController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\Termo\TermoController;
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
    Route::delete('/delete/{id}',[ContratoController::class, 'delete'])->name('contrato.delete');
});

Route::group(['prefix' => 'termo', 'namespace' => 'App\Http\Controllers\TermoController'], function() {
    Route::get('/', [TermoController::class, 'index'])->name('termo.index');
    Route::get('/list', [TermoController::class, 'list'])->name('termo.list');
    Route::get('/getByContratoId/{id}', [TermoController::class, 'getbycontratoid'])->name('termo.list');
    Route::post('/',[TermoController::class,'create'])->name('termo.create');
    Route::get('/historico/{id}',[TermoController::class, 'historico'])->name('termo.historico');
    Route::get('/{id}',[TermoController::class, 'edit'])->name('termo.edit');
    Route::post('/{id}',[TermoController::class, 'update'])->name('termo.update');
    Route::post('/{id}/delete',[TermoController::class, 'delete'])->name('termo.delete');
});

Route::group(['prefix' => 'empresa', 'namespace' => 'App\Http\Controllers\EmpresaController'], function() {
    Route::get('/', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::get('/add', [EmpresaController::class, 'add'])->name('empresa.add');
    Route::get('/list', [EmpresaController::class, 'list'])->name('empresa.list');
    Route::post('/',[EmpresaController::class,'create'])->name('empresa.create');
    Route::get('/get-by-query', [EmpresaController::class, 'getByQuery'])->name('empresa.get-by-query');
    Route::get('/historico/{id}',[EmpresaController::class, 'historico'])->name('empresa.historico');
    Route::get('/{id}',[EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::post('/{id}',[EmpresaController::class, 'update'])->name('empresa.update');
    Route::delete('/delete/{id}',[EmpresaController::class, 'delete'])->name('empresa.delete');
});

Route::group(['prefix' => 'empenho', 'namespace' => 'App\Http\Controllers\EmpenhoController'], function() {
    Route::get('/', [EmpenhoController::class, 'index'])->name('empenho.index');
    Route::get('/add', [EmpenhoController::class, 'add'])->name('empenho.add');
    Route::get('/list/{termo_id}', [EmpenhoController::class, 'list'])->name('empenho.list');
    Route::get('/listNotas/{empenho_id}', [EmpenhoController::class, 'listNotas'])->name('empenho.listNotas');
    Route::get('/termo/{id}', [EmpenhoController::class, 'termo'])->name('empenho.termo');
    Route::get('/termo/{contrato_id}/empenho/{termo_id}', [EmpenhoController::class, 'empenho'])->name('empenho.termo');
    Route::get('/termo/{contrato_id}/empenho/{termo_id}/nota-fiscal/{empenho_id}', [EmpenhoController::class, 'notaFiscal'])->name('empenho.nota');
    Route::post('/',[EmpenhoController::class,'createEmpenho'])->name('empenho.createEmpenho');
    Route::post('/nota-fiscal',[EmpenhoController::class,'createNotaFiscal'])->name('empenho.createNotaFiscal');
    Route::get('/get-by-query', [EmpenhoController::class, 'getByQuery'])->name('empenho.get-by-query');
    Route::get('/historico/{id}',[EmpenhoController::class, 'historico'])->name('empenho.historico');
    Route::get('/{id}',[EmpenhoController::class, 'edit'])->name('empenho.edit');
    Route::post('/{id}',[EmpenhoController::class, 'update'])->name('empenho.update');
    Route::delete('/delete/{id}',[EmpenhoController::class, 'delete'])->name('empenho.delete');
});

Route::group(['prefix' => 'nota-fiscal', 'namespace' => 'App\Http\Controllers\PessoaController'], function() {
    Route::get('/', [PessoaController::class, 'index'])->name('pessoa.index');
    Route::get('/list', [PessoaController::class, 'list'])->name('pessoa.list');
    Route::post('/',[PessoaController::class,'create'])->name('pessoa.create');
    Route::get('/historico/{id}',[PessoaController::class, 'historico'])->name('pessoa.historico');
    Route::get('/{id}',[PessoaController::class, 'edit'])->name('pessoa.edit');
    Route::post('/{id}',[PessoaController::class, 'update'])->name('pessoa.update');
    Route::delete('/delete/{id}',[PessoaController::class, 'delete'])->name('pessoa.delete');
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
