<?php

namespace App\Http\Controllers\TermoAditivo;

use App\Databases\Contracts\TermoAditivoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermoAditivoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermoAditivoController extends Controller
{
    public function __construct(private TermoAditivoContract $repository){}

    public function list(Request $request): JsonResponse
    {
        $dados = $this->repository->getAll($request->all());
        return response()->json([
            'data' => $dados->all(),
            'recordsFiltered' => $dados->total(),
            'recordsTotal' => $dados->total()
        ]);
    }


    public function create(TermoAditivoRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $TermoAditivo = $this->repository->getById($id);
        return response()->json($TermoAditivo);
    }

    /**
     *
     */
    public function update(TermoAditivoRequest $request, int $id): JsonResponse
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

