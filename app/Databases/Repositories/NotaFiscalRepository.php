<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\NotaFiscalContract;
use App\Databases\Models\NotaFiscal;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NotaFiscalRepository implements NotaFiscalContract {

    public function __construct(private NotaFiscal $model){}

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
            $NotaFiscal = new NotaFiscal([
                'nome' => $params['nome'],
                'cpf' => $params['cpf'],
            ]);
            $NotaFiscal->save();

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
            $NotaFiscal = $this->getById($id);
            $NotaFiscal->update([
                'nome' => $params['nome'],
                'cpf' => $params['cpf'],
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
            $NotaFiscal = $this->getById($id);
            $NotaFiscal->delete();
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
        $query = NotaFiscal::query();
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
        return NotaFiscal::query()->where('id', $id)->firstOrFail();
    }


}
