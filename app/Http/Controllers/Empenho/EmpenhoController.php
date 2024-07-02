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

    public function index($id_contrato, $id_termo): View
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

        return view('empenho.controle_financeiro', compact('id_contrato', 'nome', 'numero_contrato', 'termoStr','id_termo'));
    }

    public function notaFiscal($id_contrato, $id_termo, $id_empenho): View
    {
        $contrato = $this->repository->getContratoById($id_contrato);
        $termo = $this->repository->getTermoById($id_termo);
        $nome = $contrato->empresa->nome;
        $numero_contrato = $contrato->numero;
        $empenho = $this->repository->getById($id_empenho);
        $numero_empenho = $empenho->empenho;
        $termoMap = [
            '0' => 'Contrato Inicial',
            '1' => '1° Termo Aditivo',
            '2' => '2° Termo Aditivo',
            '3' => '3° Termo Aditivo',
            '4' => '4° Termo Aditivo',
            '5' => '5° Termo Aditivo'
        ];
        $termoStr = $termoMap[$termo->numero] ?? 'Termo Desconhecido';

        return view('nota_fiscal.index', compact('id_contrato', 'nome', 'numero_contrato', 'termoStr','id_empenho','numero_empenho'));
    }

    public function list(Request $request, $termo_id): JsonResponse
    {
        $dados = $this->repository->getAllEmpenhos($request->all(), $termo_id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }

    public function listNotas(Request $request, $empenho_id): JsonResponse
    {
        $dados = $this->repository->getAllNotas($request->all(), $empenho_id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function create(EmpenhoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }


    public function edit(int $id): JsonResponse
    {
        $empenho = $this->repository->getById($id);
        return response()->json($empenho);
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

