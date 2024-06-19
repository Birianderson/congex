<?php

namespace App\Http\Controllers\Empresa;

use App\Databases\Contracts\EmpresaContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpresaController extends Controller
{
    public function __construct(private EmpresaContract $repository){}

    public function index(Request $request): View
    {
        return view('empresa.index');
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


    public function create(EmpresaRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id){
        $contrato = $this->repository->getById($id);
        $documentos_contrato = $this->repository->getAllDocumentoContrato($id);
        $all_documentos = $this->repository->getAllTipoDocumentoContrato();
        return view('contrato.edit', compact('contrato', 'documentos_contrato','all_documentos'));
    }

    public function update(ContratoRequest $request, int $id){
        $params = $request->except('_token');
        $contrato = $this->repository->getById($id);
        $documentos_contrato = $this->repository->getAllDocumentoContrato($id);
        $all_documentos = $this->repository->getAllTipoDocumentoContrato();
        $this->repository->update($id, $params, $contrato, $all_documentos,$documentos_contrato);
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

