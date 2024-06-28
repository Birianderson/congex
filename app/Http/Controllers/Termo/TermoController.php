<?php

namespace App\Http\Controllers\Termo;

use App\Databases\Contracts\TermoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermoController extends Controller
{
    public function __construct(private TermoContract $repository){}

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

}

