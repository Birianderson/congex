<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\ContratoContract;
use App\Databases\Models\Contrato;
use App\Databases\Models\Responsabilidade;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
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
                $params['situacao'] = 'V';
                $params['ativo'] = 'S';
            } else {
                $params['situacao'] = 'NV';
                $params['ativo'] = 'N';
            }

            $contrato = new Contrato([
                'numero' => $params['numero'],
                'objeto' => $params['objeto'],
                'situacao' => $params['situacao'],
                'ativo' => $params['ativo'],
                'empresa_id' => $params['empresa_id'],
                'oberservacao' => $params['observacao'],
                'licitacao_id' => $params['licitacao_id'] ?? null,
                'data_inicio' => $params['data_inicio'],
                'data_fim' => $params['data_fim'],
                'valor' => $params['valor'],
            ]);
            $contrato->save();

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
                'ativo' => $params['ativo'],
                'empresa_id' => $params['empresa_id'],
                'oberservacao' => $params['observacao'],
                'licitacao_id' => $params['licitacao_id'] ?? null,
                'data_inicio' => $params['data_inicio'],
                'data_fim' => $params['data_fim'],
                'valor' => $params['valor'],
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

            // Excluir os "filhos" relacionados ao contrato
            $contrato->nota_fiscal()->delete();
            $contrato->documento_contrato()->delete();
            $contrato->termo_aditivo()->delete();
            $contrato->notificacao()->delete();

            // Excluir o contrato
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
        $query = Contrato::query()->with(['empresa', 'responsabilidades']);
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



    public function getById(int $id): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        return Contrato::query()
            ->with(['empresa', 'responsabilidades'])
            ->where('id', $id)
            ->firstOrFail();
    }

}
