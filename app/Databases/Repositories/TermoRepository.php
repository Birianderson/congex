<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\TermoContract;
use App\Databases\Models\Arquivo;
use App\Databases\Models\Contrato;
use App\Databases\Models\Termo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TermoRepository implements TermoContract {

    public function __construct(private Termo $model){}

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
            $dataHoje = Carbon::today();
            $dataInicio = Carbon::createFromFormat('Y-m-d', $params['data_inicio']);
            $dataFim = Carbon::createFromFormat('Y-m-d', $params['data_fim']);
            if ($dataInicio->isBefore($dataHoje) && $dataFim->isAfter($dataHoje)) {
                $params['ativo'] = 'S';
            } else {
                $params['ativo'] = 'N';
            }
            $ultima = Termo::query()->where('contrato_id',$params['contrato_id'])->orderBy('numero', 'desc')->first();
            if ($ultima == null) {
                $params['numero'] = 1;
            } else {
                $numero = $ultima->numero + 1 ?? 1;
                $params['numero'] = $numero;
            }
            $termo = new Termo([
                'contrato_id' => $params['contrato_id'],
                'observacao' => $params['observacao'],
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'ativo' => $params['ativo'],
                'valor'=>  $params['valor'],
                'valor_pago'=>  0,
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
            $Termo = $this->getById($id);
            $Termo->update([
                'observacao' => $params['observacao'] ?? '',
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'valor'=>  $params['valor'],
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
            $Termo = $this->getById($id);
            $Termo->delete();
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
    public function getByContratoID($id): LengthAwarePaginator
    {
        $query = Termo::query()->where('contrato_id', $id);
        $page = (($params['start'] ?? 0) / ($params['length'] ?? 10) + 1);
        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params): LengthAwarePaginator
    {
        $query = Termo::query();
        $page = (($params['start'] ?? 0) / ($params['length'] ?? 10) + 1);
        return $query->paginate($params['length'] ?? 10, ['*'], 'page', $page);
    }

    public function getContratoById(int $id): Model
    {
        return Contrato::query()->where('id', $id)->firstOrFail();
    }


    public function getById(int $id): Model
    {
        return Termo::query()->where('id', $id)->firstOrFail();
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

    public function getArquivos(int $id): array|\Illuminate\Database\Eloquent\Collection
    {
        return Arquivo::query()->where('tabela', 'termo')->where('chave', $id)->get();
    }

    public function createArquivos(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            // Percorrer os arquivos enviados
            foreach ($params['tipo_arquivo_id'] as $index => $tipoArquivoId) {
                // Verificar se há um arquivo existente
                $arquivoExistente = Arquivo::where('tabela', 'termo')
                    ->where('chave', $params['chave'])
                    ->where('tipo_arquivo_id', $tipoArquivoId)
                    ->first();

                // Se um novo arquivo foi enviado, faça o upload e substitua o existente
                if (isset($params['arquivos'][$index]) && $params['arquivos'][$index] instanceof UploadedFile) {
                    if ($arquivoExistente) {
                        $arquivoExistente->delete();
                    }
                    $path = $params['arquivos'][$index]->store('/public');
                    $arquivo = new Arquivo([
                        'nome' => $params['nome'][$index] ?? '',
                        'descricao' => $params['descricao'][$index] ?? '',
                        'tabela' => 'termo',
                        'path' => $path,
                        'chave' => $params['chave'],
                        'tipo_arquivo_id' => $tipoArquivoId,
                    ]);
                    $arquivo->save();
                } elseif ($arquivoExistente) {
                    // Se nenhum novo arquivo foi enviado, apenas atualize o nome e a descrição
                    $arquivoExistente->nome = $params['nome'][$index] ?? $arquivoExistente->nome;
                    $arquivoExistente->descricao = $params['descricao'][$index] ?? $arquivoExistente->descricao;
                    $arquivoExistente->save();
                }
            }

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }
}
