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

    public function edit(int $id): JsonResponse
    {
        $empresa = $this->repository->getById($id);
        return response()->json($empresa);
    }

    /**
     *
     */
    public function update(EmpresaRequest $request, int $id): JsonResponse
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

