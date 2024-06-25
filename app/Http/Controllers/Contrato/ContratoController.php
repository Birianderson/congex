<?php

namespace App\Http\Controllers\Contrato;

use App\Databases\Contracts\ContratoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContratoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    public function __construct(private ContratoContract $repository){}

    public function index(Request $request)
    {
        return view('contrato.index');
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

    public function create(ContratoRequest $request):JsonResponse {
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id){
        $contrato = $this->repository->getById($id);
        return response()->json($contrato);
    }

    public function update(ContratoRequest $request, int $id){
        $params = $request->except('_token');
        $this->repository->update($id,$params);
        return redirect(route("contrato.index", ));
    }
    public function historico($id){
        $contrato = $this->repository->getById($id);
        $documentos = $this->repository->getDocsByLicitacaoId($contrato->licitacao_id);
        $termos_aditivos = $this->repository->getTermoAditivoById($id);
        $documentos_contrato = $this->repository->getAllDocumentoContrato($id);
        return view('contrato.historico', compact('contrato', 'documentos', 'termos_aditivos','documentos_contrato'));
    }
    public function delete(int $id){
        $this->repository->destroy($id);
        return redirect(route("contrato.index"));
    }

}

