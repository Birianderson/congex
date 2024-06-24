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
class ContratoRepository implements ContratoContract {

    public function __construct(private Contrato $model){}

    /**
     * @throws Exception
     */
    public function create(array $params, bool $autoCommit = true): bool
    {
        if($autoCommit) DB::beginTransaction();
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
                'objeto'=>$params['objeto'],
                'situacao'=>$params['situacao'],
                'ativo' => $params['ativo'],
                'empresa_id'=>$params['empresa_id'],
                'oberservacao' => $params['observacao'],
                'licitacao_id'=>$params['licitacao_id'] ?? null,
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'valor'=> $params['valor'],
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

            /*
                        foreach ($params as $key => $file) {
                            if ($file instanceof UploadedFile) {
                                $key = str_replace('_', ' ', $key);
                                $path = $file->store('/public');
                                $arquivo = new DocumentoContrato([
                                    'nome' => $key,
                                    'tipo' => 'contrato',
                                    'path' => $path,
                                    'contrato_id' => $contrato['id'],
                                    'tipo_id' => $contrato['id']
                                ]);
                                $arquivo->save();
                            }
                        }
            */
            if($autoCommit) DB::commit();
            return true;
        } catch(Exception $ex) {
            if($autoCommit) DB::rollBack();
            throw new Exception($ex);
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $params, Model $contrato, $all_documentos, $documentos_contrato , bool $autoCommit = true): bool{
        if($autoCommit) DB::beginTransaction();
        try {
            $valorNumerico = preg_replace("/[^0-9]/", "", $params['valor']);

            Contrato::where('id',$id)->update([
                'numero' => $params['numero'],
                'fiscal'=>$params['fiscal'],
                'objeto'=>$params['objeto'],
                'cnpj' => $params['cnpj'],
                'empresa' => $params['empresa'],
                'oberservacao' => $params['oberservacao'],
                'licitacao_id'=>$params['licitacao_id'],
                'situacao' => $params['situacao'],
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'valor'=>$valorNumerico,
            ]);

            if($autoCommit) DB::commit();
            return true;
        } catch(Exception $ex) {
            if($autoCommit) DB::rollBack();
            throw new Exception($ex);
        }
    }

    public function destroy(int $id, bool $autoCommit = true): bool{
        if($autoCommit) DB::beginTransaction();
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
            if($autoCommit) DB::commit();
            return true;
        }  catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex);
        }
    }
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params): LengthAwarePaginator
    {
        $query = Contrato::query();
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
            $query->orderBy('valor', 'asc');
        }
        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }


    public function getById(int $id): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        return Contrato::query()
            ->with(['termo_aditivo', 'documento_contrato' => function ($query) {
                $query->where('tipo', 'termo_aditivo');
            }])
            ->orderBy('empresa')
            ->where('id', $id)
            ->firstOrFail();
    }

}
