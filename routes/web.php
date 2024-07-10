<?php

use App\Http\Controllers\Cargo\CargoController;
use App\Http\Controllers\Contrato\ContratoController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Empenho\EmpenhoController;
use App\Http\Controllers\NotaFiscal\NotaFiscalController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\RiscoContrato\RiscoContratoController;
use App\Http\Controllers\Termo\TermoController;
use App\Http\Controllers\TipoArquivo\TipoArquivoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'contrato', 'namespace' => 'App\Http\Controllers\ContratoController'], function() {
    Route::get('/', [ContratoController::class, 'index'])->name('contrato.index');
    Route::get('/controle_financeiro', [ContratoController::class, 'controle_financeiro'])->name('contrato.controle_financeiro');
    Route::get('/list', [ContratoController::class, 'list'])->name('contrato.list');
    Route::get('/get_arquivos/{id}',[ContratoController::class, 'get_arquivos'])->name('contrato.get_arquivos');
    Route::post('/create_arquivos/{id}',[ContratoController::class, 'create_arquivos'])->name('contrato.create_arquivos');
    Route::post('/',[ContratoController::class,'create'])->name('contrato.create');
    Route::get('/historico/{id}',[ContratoController::class, 'historico'])->name('contrato.historico');
    Route::get('/{id}',[ContratoController::class, 'edit'])->name('contrato.edit');
    Route::get('/download/{file}',[ContratoController::class, 'download'])->name('contrato.download');
    Route::post('/{id}',[ContratoController::class, 'update'])->name('contrato.update');
    Route::delete('/delete/{id}',[ContratoController::class, 'delete'])->name('contrato.delete');
});

Route::group(['prefix' => 'termo', 'namespace' => 'App\Http\Controllers\TermoController'], function() {
    Route::get('/controle_financeiro/{id}', [TermoController::class, 'index'])->name('termo.controle_financeiro');
    Route::get('/list', [TermoController::class, 'list'])->name('termo.list');
    Route::get('/getByContratoId/{id}', [TermoController::class, 'getbycontratoid'])->name('termo.list');
    Route::post('/',[TermoController::class,'create'])->name('termo.create');
    Route::get('/get_arquivos/{id}',[TermoController::class, 'get_arquivos'])->name('termo.get_arquivos');
    Route::post('/create_arquivos/{id}',[TermoController::class, 'create_arquivos'])->name('termo.create_arquivos');
    Route::get('/download/{file}',[TermoController::class, 'download'])->name('termo.download');
    Route::get('/historico/{id}',[TermoController::class, 'historico'])->name('termo.historico');
    Route::get('/{id}',[TermoController::class, 'edit'])->name('termo.edit');
    Route::post('/{id}',[TermoController::class, 'update'])->name('termo.update');
    Route::delete('/delete/{id}',[TermoController::class, 'delete'])->name('termo.delete');
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
    Route::get('/controle_financeiro/{id_contrato}/termo/{id_termo}', [EmpenhoController::class, 'index'])->name('empenho.controle_financeiro');
    Route::get('/list/{termo_id}', [EmpenhoController::class, 'list'])->name('empenho.list');
    Route::get('/termo/{id}', [EmpenhoController::class, 'termo'])->name('empenho.termo');
    Route::get('/get_arquivos/{id}',[EmpenhoController::class, 'get_arquivos'])->name('empenho.get_arquivos');
    Route::post('/create_arquivos/{id}',[EmpenhoController::class, 'create_arquivos'])->name('empenho.create_arquivos');
    Route::get('/download/{file}',[EmpenhoController::class, 'download'])->name('empenho.download');
    Route::post('/',[EmpenhoController::class,'create'])->name('empenho.create');
    Route::get('/get-by-query', [EmpenhoController::class, 'getByQuery'])->name('empenho.get-by-query');
    Route::get('/{id}',[EmpenhoController::class, 'edit'])->name('empenho.edit');
    Route::post('/{id}',[EmpenhoController::class, 'update'])->name('empenho.update');
    Route::delete('/delete/{id}',[EmpenhoController::class, 'delete'])->name('empenho.delete');
});

Route::group(['prefix' => 'nota-fiscal', 'namespace' => 'App\Http\Controllers\PessoaController'], function() {
    Route::get('/controle_financeiro/{id_empenho}', [NotaFiscalController::class, 'index'])->name('nota_fiscal.controle_financeiro');
    Route::get('/list/{id}', [NotaFiscalController::class, 'list'])->name('nota_fiscal.list');
    Route::post('/',[NotaFiscalController::class,'create'])->name('nota_fiscal.create');
    Route::get('/get_arquivos/{id}',[NotaFiscalController::class, 'get_arquivos'])->name('nota_fiscal.get_arquivos');
    Route::post('/create_arquivos/{id}',[NotaFiscalController::class, 'create_arquivos'])->name('nota_fiscal.create_arquivos');
    Route::get('/download/{file}',[NotaFiscalController::class, 'download'])->name('nota_fiscal.download');
    Route::get('/historico/{id}',[NotaFiscalController::class, 'historico'])->name('nota_fiscal.historico');
    Route::get('/{id}',[NotaFiscalController::class, 'edit'])->name('nota_fiscal.edit');
    Route::post('/{id}',[NotaFiscalController::class, 'update'])->name('nota_fiscal.update');
    Route::delete('/delete/{id}',[NotaFiscalController::class, 'delete'])->name('nota_fiscal.delete');
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

Route::group(['prefix' => 'risco-contrato', 'namespace' => 'App\Http\Controllers\RiscoContratoController'], function() {
    Route::get('/', [RiscoContratoController::class, 'index'])->name('risco.index');
    Route::get('/list', [RiscoContratoController::class, 'list'])->name('risco.list');
    Route::post('/create/{id_contrato}',[RiscoContratoController::class,'create'])->name('risco.create');
    Route::get('/historico/{id}',[RiscoContratoController::class, 'historico'])->name('risco.historico');
    Route::get('/{id}',[RiscoContratoController::class, 'edit'])->name('risco.edit');
    Route::post('/update/{id_contrato}',[RiscoContratoController::class, 'update'])->name('risco.update');
    Route::delete('/delete/{id}',[RiscoContratoController::class, 'delete'])->name('risco.delete');
});

Route::group(['prefix' => 'tipo-arquivo', 'namespace' => 'App\Http\Controllers\TipoArquivoController'], function() {
    Route::get('/', [TipoArquivoController::class, 'index'])->name('tipo-arquivo.index');
    Route::get('/list', [TipoArquivoController::class, 'list'])->name('tipo-arquivo.list');
    Route::get('/getByTabela/{tabela}',[TipoArquivoController::class, 'getByTabela'])->name('tipo-arquivo.getByTabela');
    Route::post('/',[TipoArquivoController::class,'create'])->name('tipo-arquivo.create');
    Route::get('/historico/{id}',[TipoArquivoController::class, 'historico'])->name('tipo-arquivo.historico');
    Route::get('/{id}',[TipoArquivoController::class, 'edit'])->name('tipo-arquivo.edit');
    Route::post('/{id}',[TipoArquivoController::class, 'update'])->name('tipo-arquivo.update');
    Route::delete('/delete/{id}',[TipoArquivoController::class, 'delete'])->name('tipo-arquivo.delete');
});

Route::group(['prefix' => 'dashboard', 'namespace' => 'App\Http\Controllers\DashboardController'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});
