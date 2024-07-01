<?php

namespace App\Http\Controllers\NotaFiscal;

use App\Databases\Contracts\NotaFiscalContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotaFiscalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotaFiscalController extends Controller
{
    public function __construct(private NotaFiscalContract $repository){}

    public function index(Request $request): View
    {
        return view('NotaFiscal.index');
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

}

