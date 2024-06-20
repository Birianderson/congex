<?php

namespace App\Http\Controllers\Fiscal;

use App\Databases\Contracts\FiscalContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\FiscalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FiscalController extends Controller
{
    public function __construct(private FiscalContract $repository){}

    public function index(Request $request): View
    {
        return view('fiscal.index');
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


    public function create(FiscalRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $fiscal = $this->repository->getById($id);
        return response()->json($fiscal);
    }

    /**
     *
     */
    public function update(FiscalRequest $request, int $id): JsonResponse
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

