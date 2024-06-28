<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\TermoAditivoContract;
use App\Databases\Models\Contrato;
use App\Databases\Models\TermoAditivo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TermoAditivoRepository implements TermoAditivoContract {

    public function __construct(private TermoAditivo $model){}

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

            $ultima = TermoAditivo::query()->where('contrato_id',$params['contrato_id'])->orderBy('numero', 'desc')->first();
            if ($ultima == null) {
                $params['numero'] = 1;
            } else {
                $numero = $ultima->numero + 1 ?? 1;
                $params['numero'] = $numero;
            }
            $termo = new TermoAditivo([
                'contrato_id' => $params['contrato_id'],
                'observacao' => $params['observacao'],
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'valor'=>  $params['valor'],
                'numero'=> $params['numero']
            ]);
            $termo->save();

            $this->verificaSituacao($termo->contrato_id);
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
            $TermoAditivo = $this->getById($id);
            $TermoAditivo->update([
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
            $TermoAditivo = $this->getById($id);
            $TermoAditivo->delete();
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
        $query = TermoAditivo::query();
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
        return TermoAditivo::query()->where('id', $id)->firstOrFail();
    }


    public function verificaSituacao($contrato_id)
    {
        $contrato = Contrato::query()
            ->where('id', '=',$contrato_id)
            ->firstOrFail();
        $dataHoje = Carbon::today();
        $termos = TermoAditivo::where('contrato_id', $contrato_id)->orderby('numero')->get();
        foreach ($termos as $termo) {
            $dataInicio = Carbon::createFromFormat('Y-m-d', $termo->data_inicio);
            $dataFim = Carbon::createFromFormat('Y-m-d', $termo->data_fim);
            if ($dataInicio->isBefore($dataHoje) && $dataFim->isAfter($dataHoje) || $dataInicio->isSameDay($dataHoje) || $dataFim->isSameDay($dataHoje)) {
                $contrato->situacao = 'V' . $termo->numero;
            }
        }

        $contrato->save();
    }

}
