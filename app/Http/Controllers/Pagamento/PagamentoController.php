<?php

namespace App\Http\Controllers\Pagamento;

use App\Databases\Contracts\PagamentoContract;
use App\Databases\Models\Contrato;
use App\Http\Controllers\Controller;
use App\Http\Requests\PagamentoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagamentoController extends Controller
{
    public function __construct(private PagamentoContract $repository){}

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

        // Mapeamento dos termos aditivos
        $termoMap = [
            '0' => 'Contrato Inicial',
            '1' => '1° Termo Aditivo',
            '2' => '2° Termo Aditivo',
            '3' => '3° Termo Aditivo',
            '4' => '4° Termo Aditivo',
            '5' => '5° Termo Aditivo'
        ];

        // Obtendo a string correspondente ao número do termo
        $termoStr = $termoMap[$termo->numero] ?? 'Termo Desconhecido';

        return view('pagamento.empenho', compact('id_contrato', 'nome', 'numero_contrato', 'termoStr','id_termo'));
    }

    public function list(Request $request, $termo_id): JsonResponse
    {
        $dados = $this->repository->getAll($request->all(), $termo_id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function createEmpenho(PagamentoRequest $request){
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
    public function update(PagamentoRequest $request, int $id): JsonResponse
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

