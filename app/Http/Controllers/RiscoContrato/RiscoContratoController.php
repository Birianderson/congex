<?php

namespace App\Http\Controllers\RiscoContrato;

use App\Databases\Contracts\RiscoContratoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\RiscoContratoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiscoContratoController extends Controller
{
    public function __construct(private RiscoContratoContract $repository){}

    public function index(Request $request): View
    {
        return view('risco.index');
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


    public function create(RiscoContratoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $RiscoContrato = $this->repository->getById($id);
        return response()->json($RiscoContrato);
    }

    /**
     *
     */
    public function update(RiscoContratoRequest $request, int $id): JsonResponse
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

