<?php

namespace App\Http\Controllers\Cargo;

use App\Databases\Contracts\CargoContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CargoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CargoController extends Controller
{
    public function __construct(private CargoContract $repository){}

    public function index(Request $request): View
    {
        return view('cargo.index');
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


    public function create(CargoRequest $request): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->create($params);
        return response()->json('success', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $cargo = $this->repository->getById($id);
        return response()->json($cargo);
    }

    /**
     *
     */
    public function update(CargoRequest $request, int $id): JsonResponse
    {
        $params = $request->except('_token');
        $this->repository->update($id, $params);
        return response()->json('success', 201);
    }

    public function delete(int $id): JsonResponse
    {
        $this->repository->destroy($id);
        return response()->json('success', 201);
    }

}

