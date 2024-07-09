<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\EmpenhoContract;
use App\Databases\Models\Arquivo;
use App\Databases\Models\Contrato;
use App\Databases\Models\NotaFiscal;
use App\Databases\Models\Empenho;
use App\Databases\Models\Termo;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmpenhoRepository implements EmpenhoContract {

    public function __construct(private Empenho $model){}

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
            $Empenho = new Empenho([
                'exercicio' => $params['exercicio'],
                'termo_de_referencia' => $params['termo_de_referencia'],
                'data_vigencia' => $params['data_vigencia'],
                'valor_empenho' => $params['valor_empenho'],
                'valor_liquidacao' => $params['valor_liquidacao'],
                'valor_total_pago' =>0,
                'empenho' => $params['empenho'],
                'termo_id' => $params['termo_id'],
                'observacao' => $params['observacao'] ?? '',
            ]);
            $Empenho->save();

            $autoCommit && DB::commit();
            return true;
        } catch (Exception $ex) {
            $autoCommit && DB::rollBack();
            throw new Exception($ex);
        }
    }


    /**
     * Atualizar Empenho
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
            $Empenho = $this->getById($id);
            $Empenho->update([
                'exercicio' => $params['exercicio'],
                'termo_de_referencia' => $params['termo_de_referencia'],
                'data_vigencia' => $params['data_vigencia'],
                'valor_empenho' => $params['valor_empenho'],
                'valor_liquidacao' => $params['valor_liquidacao'],
                'valor_total_pago' =>0,
                'empenho' => $params['empenho'],
                'observacao' => $params['observacao'] ?? '',
            ]);
            $this->verifyValorPago($Empenho->id);
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
            $Empenho = $this->getById($id);
            $id_termo = $Empenho->termo_id;
            $Empenho->delete();
            $this->verifyValorPagoAtDelete($id_termo);
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
    public function getAllEmpenhos(array $params, $termo_id): LengthAwarePaginator
    {
        $query = Empenho::query()->where('termo_id', $termo_id)->with('termo');
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
        return Empenho::query()->where('id', $id)->with('termo.contrato')->firstOrFail();
    }

    /**
     * Usado para busca no autocomplerar
     * @param string $query
     * @return Collection
     */
    public function getByQuery(string $query): Collection
    {
        return Empenho::query()->whereRaw('lower(nome) like ?', ["%{$query}%"])->get();
    }

    public function getContratoById(int $id): Model
    {
        return Contrato::query()->where('id', $id)->firstOrFail();
    }

    public function getTermoById(int $id): Model
    {
        return Termo::query()->where('id', $id)->firstOrFail();
    }
    public function verifyValorPagoAtDelete(int $id_termo): Model
    {
        $termo = Termo::query()->where('id', $id_termo)->firstOrFail();
        $valorPagotermo = Empenho::query()
            ->where('id_termo', $id_termo)
            ->sum('valor_total_pago');

        // Atualiza o valor pago no empenho
        $termo->valor_pago = $valorPagotermo;
        $termo->save();

        return $termo;
    }

    public function verifyValorPago(int $id_empenho): Model
    {
        $empenho = Empenho::query()->where('id', $id_empenho)->with('termo')->firstOrFail();
        // Soma os valores de todas as notas fiscais associadas ao mesmo empenho
        $valorPagoEmpenho = NotaFiscal::query()
            ->where('empenho_id', $id_empenho)
            ->sum('valor');

        // Atualiza o valor pago no empenho
        $empenho->valor_total_pago = $valorPagoEmpenho;
        $empenho->save();

        // Soma os valores pagos de todos os empenhos associados ao mesmo termo
        $valorPagoTermo = Empenho::query()
            ->where('termo_id', $empenho->termo_id)
            ->sum('valor_total_pago');

        // Atualiza o valor pago no termo
        $empenho->termo->valor_pago = $valorPagoTermo;
        $empenho->termo->save();

        return $empenho;
    }

    public function createArquivos(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            // Percorrer os arquivos enviados
            foreach ($params['tipo_arquivo_id'] as $index => $tipoArquivoId) {
                // Verificar se há um arquivo existente
                $arquivoExistente = Arquivo::where('tabela', 'empenho')
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
                        'tabela' => 'empenho',
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


    public function getArquivos(int $id): array|\Illuminate\Database\Eloquent\Collection
    {
        return Arquivo::query()->where('tabela', 'empenho')->where('chave', $id)->get();
    }
}
