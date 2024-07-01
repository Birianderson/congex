<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\ContratoContract;
use App\Databases\Models\Contrato;
use App\Databases\Models\Responsabilidade;
use App\Databases\Models\Termo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContratoRepository implements ContratoContract
{

    public function __construct(private Contrato $model)
    {
    }

    /**
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        if ($autoCommit) DB::beginTransaction();
        try {
            $dataHoje = Carbon::today();
            $dataInicio = Carbon::createFromFormat('Y-m-d', $params['data_inicio']);
            $dataFim = Carbon::createFromFormat('Y-m-d', $params['data_fim']);
            if ($dataInicio->isBefore($dataHoje) && $dataFim->isAfter($dataHoje)) {
                $params['situacao'] = 'V0';
                $params['ativo'] = 'S';
            } else {
                $params['situacao'] = 'NV';
                $params['ativo'] = 'N';
            }

            $contrato = new Contrato([
                'numero' => $params['numero'],
                'objeto' => $params['objeto'],
                'situacao' => $params['situacao'],
                'empresa_id' => $params['empresa_id'],
                'observacao' => $params['observacao'],
                'licitacao_id' => $params['licitacao_id'] ?? null,
            ]);
            $contrato->save();

            $termo = new Termo([
                'contrato_id' => $contrato->id,
                'observacao' => $params['observacao'],
                'data_inicio'=>$params['data_inicio'],
                'ativo' => $params['ativo'],
                'data_fim'=>$params['data_fim'],
                'valor'=>  $params['valor'],
                'numero'=> '0',
            ]);
            $termo->save();

            if (isset($params['cargo']) && isset($params['pessoa'])) {
                $cargos = $params['cargo'];
                $pessoas = $params['pessoa'];

                foreach ($cargos as $index => $cargoId) {
                    if (isset($pessoas[$index])) {
                        $pessoaId = $pessoas[$index];

                        $responsabilidade = new Responsabilidade([
                            'contrato_id' => $contrato->id,
                            'cargo_id' => $cargoId,
                            'pessoa_id' => $pessoaId,
                        ]);
                        $responsabilidade->save();
                    }
                }
            }
            if ($autoCommit) DB::commit();
            return true;
        } catch (Exception $ex) {
            if ($autoCommit) DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $params, bool $autoCommit = true): bool
    {
        if ($autoCommit) DB::beginTransaction();
        try {
            $contrato = $this->getById($id);
            $dataHoje = Carbon::today();
            $dataInicio = Carbon::createFromFormat('Y-m-d', $params['data_inicio']);
            $dataFim = Carbon::createFromFormat('Y-m-d', $params['data_fim']);
            if ($dataInicio->isBefore($dataHoje) && $dataFim->isAfter($dataHoje)) {
                $params['situacao'] = 'V';
                $params['ativo'] = 'S';
            } else {
                $params['situacao'] = 'NV';
                $params['ativo'] = 'N';
            }
            $contrato->update([
                'numero' => $params['numero'],
                'objeto' => $params['objeto'],
                'situacao' => $params['situacao'],
                'empresa_id' => $params['empresa_id'],
                'observacao' => $params['observacao'],
                'licitacao_id' => $params['licitacao_id'] ?? null,
            ]);

            $termo = $this->getTermoByContratoId($contrato->id);
            $termo->update([
                'observacao' => $params['observacao'],
                'data_inicio'=>$params['data_inicio'],
                'ativo' => $params['ativo'],
                'data_fim'=>$params['data_fim'],
                'valor'=>  $params['valor'],
            ]);
            Responsabilidade::where('contrato_id', $id)->delete();

            if (isset($params['cargo']) && isset($params['pessoa'])) {
                $cargos = $params['cargo'];
                $pessoas = $params['pessoa'];

                foreach ($cargos as $index => $cargoId) {
                    if (isset($pessoas[$index])) {
                        $pessoaId = $pessoas[$index];

                        $responsabilidade = new Responsabilidade([
                            'contrato_id' => $contrato->id,
                            'cargo_id' => $cargoId,
                            'pessoa_id' => $pessoaId,
                        ]);
                        $responsabilidade->save();
                    }
                }
            }
            $this->verificaSituacao($contrato->id);
            if ($autoCommit) DB::commit();
            return true;
        } catch (Exception $ex) {
            if ($autoCommit) DB::rollBack();
            throw new Exception($ex);
        }
    }

    public function destroy(int $id, bool $autoCommit = true): bool
    {
        if ($autoCommit) DB::beginTransaction();
        try {
            /// Obter o contrato pelo ID
            $contrato = Contrato::findOrFail($id);
            $contrato->termos()->delete();
            $contrato->delete();

            DB::commit();
            if ($autoCommit) DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex);
        }
    }

    public function getAll(array $params): LengthAwarePaginator
    {
        $query = Contrato::query()->with(['empresa', 'responsabilidades','termos'])
            ->selectRaw("
                CONTRATO.*,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                END AS data_inicio ,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '4')
                END AS data_fim_real ,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '4')
                END AS valor_real
            ");

        $page = (($params['start'] ?? 0) / ($params['length'] ?? 10) + 1);

        if (isset($params['search']['value']) && !empty($params['search']['value'])) {
            $search = strtolower($params['search']['value']);
            $query->whereHas('empresa', function ($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%');
            });
        }

        if (isset($params['order'][0]) && !empty($params['order'][0])) {
            $columnNumber = $params['order'][0]['column'];
            $dir = $params['order'][0]['dir'];
            $columnName = $params['columns'][$columnNumber]['data'];

            if ($columnName == 'empresa.nome') {
                $query->join('empresa', 'contrato.empresa_id', '=', 'empresa.id')
                    ->orderBy('empresa.nome', $dir);
            } else {
                $query->orderBy($columnName, $dir);
            }
        } else {
            $query->orderBy('valor', 'asc');
        }

        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }



    public function getById(int $id): Builder|Model
    {
        return Contrato::query()
            ->with(['empresa', 'responsabilidades', 'termos'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getTermoByContratoId(int $contrato_id): Builder|Model
    {
        return Termo::query()
            ->where('contrato_id', $contrato_id)
            ->firstOrFail();
    }

    public function verificaSituacao($contrato_id)
    {
        $contrato = Contrato::query()
            ->where('id', '=',$contrato_id)
            ->firstOrFail();
        $dataHoje = Carbon::today();
        $termos = Termo::where('contrato_id', $contrato_id)->orderby('numero')->get();
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
