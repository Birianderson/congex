<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\CargoContract;
use App\Databases\Models\Cargo;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CargoRepository implements CargoContract {

    public function __construct(private Cargo $model){}

    /**
     * Salvar novo categoria
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $cargo = new Cargo([
                'nome' => $params['nome'],
            ]);
            $cargo->save();

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


    /**
     * Atualizar Empresa
     * @param int $id
     * @param array $params
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function update(int $id, array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $cargo = $this->getById($id);
            $cargo->update([
                'nome' => $params['nome'],
            ]);
            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


    /**
     * Deletar categoria
     * @param int $id
     * @param bool $autoCommit
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            $cargo = $this->getById($id);
            $cargo->delete();
            $autoCommit && DB::commit();
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return true;
    }



    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params): LengthAwarePaginator
    {
        $query = Cargo::query();
        $page = (($params['start'] ?? 0) / ($params['length'] ?? 10) + 1);
        if(isset($params['search']['value']) && !empty($params['search']['value'])){
            $search = strtolower($params['search']['value']);
            $query->where('nome', 'like', '%'.$search.'%');
        }
        if(isset($params['order'][0]) && !empty($params['order'][0])){
            $columnNumber = $params['order'][0]['column'];
            $dir = $params['order'][0]['dir'];
            $columnName = $params['columns'][$columnNumber]['data'];
            $query->orderBy($columnName, $dir);
        }else{
            $query->orderBy('nome', 'asc');
        }
        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }


    public function getById(int $id): Model
    {
        return Cargo::query()->where('id', $id)->firstOrFail();
    }


}
