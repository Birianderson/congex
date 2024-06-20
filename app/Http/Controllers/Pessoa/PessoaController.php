<?php

namespace App\Http\Controllers\Pessoa;

use App\Databases\Contracts\PessoaContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\PessoaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PessoaController extends Controller
{
    public function __construct(private PessoaContract $repository){}

    public function index(Request $request): View
    {
        return view('pessoa.index');
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


    public function create(PessoaRequest $request){
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $Pessoa = $this->repository->getById($id);
        return response()->json($Pessoa);
    }

    /**
     *
     */
    public function update(PessoaRequest $request, int $id): JsonResponse
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

