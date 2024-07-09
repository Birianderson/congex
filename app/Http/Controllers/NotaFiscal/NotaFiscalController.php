<?php

namespace App\Http\Controllers\NotaFiscal;

use App\Databases\Contracts\NotaFiscalContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArquivoRequest;
use App\Http\Requests\NotaFiscalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotaFiscalController extends Controller
{
    public function __construct(private NotaFiscalContract $repository){}

    public function index($id_empenho): View
    {
        $empenho = $this->repository->getEmpenhoById($id_empenho);
        $termo = $empenho->termo;
        $nome = $empenho->termo->contrato->empresa->nome;
        $numero_contrato = $empenho->termo->contrato->numero;
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

        return view('nota_fiscal.controle_financeiro', compact('nome', 'numero_contrato', 'termoStr','id_empenho','numero_empenho'));
    }

    public function list(Request $request, $id): JsonResponse
    {
        $dados = $this->repository->getAll($request->all(), $id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function create(NotaFiscalRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $NotaFiscal = $this->repository->getById($id);
        return response()->json($NotaFiscal);
    }

    /**
     *
     */
    public function update(NotaFiscalRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->update($id, $params);
        return response()->json('success', 201);
    }
    public function delete(int $id){
        $this->repository->destroy($id);
        return response()->json('success', 201);
    }

    public function get_arquivos(int $id): JsonResponse
    {
        $arquivos = $this->repository->getArquivos($id);
        return response()->json($arquivos);
    }

    public function create_arquivos(ArquivoRequest $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->createArquivos($params);
        return response()->json('success', 201);
    }

    public function download($file) {
        $filePath = storage_path('app/public/' . base64_decode($file));

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Arquivo não encontrado.'], 404);
        }

        return response()->download($filePath);
    }
}

