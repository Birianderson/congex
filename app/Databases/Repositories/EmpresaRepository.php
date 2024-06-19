<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\EmpresaContract;
use App\Databases\Models\Empresa;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmpresaRepository implements EmpresaContract {

    public function __construct(private Empresa $model){}

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
            $empresa = new Empresa([
                'nome' => $params['nome'],
                'cnpj' => $params['cnpj'],
            ]);
            $empresa->save();

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params): LengthAwarePaginator
    {
        $query = Empresa::query();
        $page = (($params['start'] ?? 0) / ($params['length'] ?? 10) + 1);
        if(isset($params['search']['value']) && !empty($params['search']['value'])){
            $search = strtolower($params['search']['value']);
            $query->where('nome', 'like', '%'.$search.'%');
        }

        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }


    public function getById(int $id): Model
    {
        return Empresa::query()
            ->with(['termo_aditivo', 'documento_contrato' => function ($query) {
                $query->where('tipo', 'termo_aditivo');
            }])
            ->orderBy('empresa')
            ->where('id', $id)
            ->firstOrFail();
    }

}
