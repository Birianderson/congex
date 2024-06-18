<?php

namespace App\Http\Controllers\Contrato;

use App\Databases\Contracts\ContratoContract;
use App\Databases\Contracts\LicitacaoContract;
use App\Databases\Models\Contrato;
use App\Databases\Repositories\ContratoRepository;
use App\Http\Requests\ContratoRequest;
use App\Http\Requests\LicitacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    public function __construct(private ContratoContract $repository){}

    public function index(Request $request)
    {
        $params = $request->all();
        $contratos = $this->repository->getAll($request->all());
        $sortDir = $request->get('sort_dir', 'asc');
        return view('contrato.index', compact('contratos', 'sortDir'));
    }

    public function add()
    {
        $documentos = $this->repository->getAllTipoDocumentoContrato();
        $licitacao_sem_ref = $this->repository->getLicitacaoSemReferencia();
        return view('contrato.add', compact('licitacao_sem_ref', 'documentos'));
    }

    public function create(ContratoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return redirect(route("contrato.index"));
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

