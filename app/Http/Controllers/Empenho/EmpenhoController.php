<?php

namespace App\Http\Controllers\Empenho;

use App\Databases\Contracts\EmpenhoContract;
use App\Databases\Models\Contrato;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmpenhoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpenhoController extends Controller
{
    public function __construct(private EmpenhoContract $repository){}

    public function index(Request $request): View
    {
        return view('pagamento.index');
    }

    public function termo($id_contrato): View
    {
        $contrato = $this->repository->getContratoById($id_contrato);
        $nome = $contrato->empresa->nome;
        $numero_contrato = $contrato->numero;
        return view('pagamento.termo', compact('id_contrato','nome','numero_contrato'));
    }

    public function empenho($id_contrato, $id_termo): View
    {
        $contrato = $this->repository->getContratoById($id_contrato);
        $termo = $this->repository->getTermoById($id_termo);
        $nome = $contrato->empresa->nome;
        $numero_contrato = $contrato->numero;
        $termoMap = [
            '0' => 'Contrato Inicial',
            '1' => '1° Termo Aditivo',
            '2' => '2° Termo Aditivo',
            '3' => '3° Termo Aditivo',
            '4' => '4° Termo Aditivo',
            '5' => '5° Termo Aditivo'
        ];
        $termoStr = $termoMap[$termo->numero] ?? 'Termo Desconhecido';

        return view('pagamento.empenho', compact('id_contrato', 'nome', 'numero_contrato', 'termoStr','id_termo'));
    }

    public function notaFiscal($id_contrato, $id_termo, $id_pagamento): View
    {
        $contrato = $this->repository->getContratoById($id_contrato);
        $termo = $this->repository->getTermoById($id_termo);
        $nome = $contrato->empresa->nome;
        $numero_contrato = $contrato->numero;
        $pagamento = $this->repository->getById($id_pagamento);
        $numero_empenho = $pagamento->empenho;
        $termoMap = [
            '0' => 'Contrato Inicial',
            '1' => '1° Termo Aditivo',
            '2' => '2° Termo Aditivo',
            '3' => '3° Termo Aditivo',
            '4' => '4° Termo Aditivo',
            '5' => '5° Termo Aditivo'
        ];
        $termoStr = $termoMap[$termo->numero] ?? 'Termo Desconhecido';

        return view('nota_fiscal.index', compact('id_contrato', 'nome', 'numero_contrato', 'termoStr','id_pagamento','numero_empenho'));
    }

    public function list(Request $request, $termo_id): JsonResponse
    {
        $dados = $this->repository->getAllPagamentos($request->all(), $termo_id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }

    public function listNotas(Request $request, $pagamento_id): JsonResponse
    {
        $dados = $this->repository->getAllNotas($request->all(), $pagamento_id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function createEmpenho(EmpenhoRequest $request){
        $params = $request->except('_token');
        $this->repository->createEmpenho($params);
        return response()->json('success', 201);
    }


    public function edit(int $id): JsonResponse
    {
        $Pagamento = $this->repository->getById($id);
        return response()->json($Pagamento);
    }

    public function getByQuery(Request $request): JsonResponse {
        return response()->json($this->repository->getByQuery($request->query('q'))->toArray());
    }


    /**
     *
     */
    public function update(EmpenhoRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->update($id, $params);
        return response()->json('success', 201);
    }
    public function delete(int $id){
        $this->repository->destroy($id);
        return response()->json('success', 201);
    }

}

