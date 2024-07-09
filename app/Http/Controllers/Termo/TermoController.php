<?php

namespace App\Http\Controllers\Termo;

use App\Databases\Contracts\TermoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArquivoRequest;
use App\Http\Requests\TermoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermoController extends Controller
{
    public function __construct(private TermoContract $repository){}


    public function index($id_contrato): View
    {
        $contrato = $this->repository->getContratoById($id_contrato);
        $nome = $contrato->empresa->nome;
        $numero_contrato = $contrato->numero;
        return view('termo.index', compact('id_contrato','nome','numero_contrato'));
    }
    public function list(Request $request): JsonResponse
    {
        $dados = $this->repository->getAll($request->all());
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function getbycontratoid($id): JsonResponse
    {
        $dados = $this->repository->getByContratoID($id);
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }

    public function create(TermoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $Termo = $this->repository->getById($id);
        return response()->json($Termo);
    }

    /**
     *
     */
    public function update(TermoRequest $request, int $id): JsonResponse
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

