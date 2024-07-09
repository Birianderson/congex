<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\NotaFiscalContract;
use App\Databases\Models\Arquivo;
use App\Databases\Models\Empenho;
use App\Databases\Models\NotaFiscal;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
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
                'valor' => $params['valor'],
                'data_pagamento' => $params['data_pagamento'],
                'data_liquidacao' => $params['data_liquidacao'],
                'liquidacao' => $params['liquidacao'],
                'nfe' => $params['nfe'],
                'empenho_id' => $params['empenho_id'],
                'ordem_servico' => $params['ordem_servico'],
                'observacao' => $params['observacao'],
                'ci' => $params['ci'],
            ]);
            $NotaFiscal->save();
            $this->verifyValorPago($NotaFiscal->id);
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
                'valor' => $params['valor'],
                'data_pagamento' => $params['data_pagamento'],
                'data_liquidacao' => $params['data_liquidacao'],
                'liquidacao' => $params['liquidacao'],
                'nfe' => $params['nfe'],
                'ordem_servico' => $params['ordem_servico'],
                'observacao' => $params['observacao'],
                'ci' => $params['ci'],
            ]);
            $this->verifyValorPago($NotaFiscal->id);
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
            $id_empenho = $NotaFiscal->empenho_id;
            $NotaFiscal->delete();
            $this->verifyValorPagoAtDelete($id_empenho);
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
    public function getAll(array $params, $id): LengthAwarePaginator
    {
        $query = NotaFiscal::query()->where('empenho_id', $id);
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

    public function verifyValorPago(int $id): Model
    {
        // Recupera a nota fiscal com o empenho e termo associados
        $nota = NotaFiscal::query()->where('id', $id)->with('empenho.termo')->firstOrFail();

        // Soma os valores de todas as notas fiscais associadas ao mesmo empenho
        $valorPagoEmpenho = NotaFiscal::query()
            ->where('empenho_id', $nota->empenho_id)
            ->sum('valor');

        // Atualiza o valor pago no empenho
        $nota->empenho->valor_total_pago = $valorPagoEmpenho;
        $nota->empenho->save();

        // Soma os valores pagos de todos os empenhos associados ao mesmo termo
        $valorPagoTermo = Empenho::query()
            ->where('termo_id', $nota->empenho->termo_id)
            ->sum('valor_total_pago');

        // Atualiza o valor pago no termo
        $nota->empenho->termo->valor_pago = $valorPagoTermo;
        $nota->empenho->termo->save();

        return $nota;
    }

    public function verifyValorPagoAtDelete(int $id_empenho): Model
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
    public function getById(int $id): Model
    {
        return NotaFiscal::query()->where('id', $id)->firstOrFail();
    }

    public function getEmpenhoById(int $id): Model
    {
        return Empenho::query()->where('id', $id)->with('termo.contrato.empresa')->firstOrFail();
    }

    public function getArquivos(int $id): array|\Illuminate\Database\Eloquent\Collection
    {
        return Arquivo::query()->where('tabela', 'nota_fiscal')->where('chave', $id)->get();
    }

    public function createArquivos(array $params, bool $autoCommit = true): bool
    {
        $autoCommit && DB::beginTransaction();
        try {
            // Percorrer os arquivos enviados
            foreach ($params['tipo_arquivo_id'] as $index => $tipoArquivoId) {
                // Verificar se há um arquivo existente
                $arquivoExistente = Arquivo::where('tabela', 'nota_fiscal')
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
                        'tabela' => 'nota_fiscal',
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
