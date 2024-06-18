<?php

namespace App\Databases\Repositories;

use App\Databases\Contracts\ContratoContract;
use App\Databases\Contracts\LicitacaoContract;
use App\Databases\Models\Contrato;
use App\Databases\Models\Documento;
use App\Databases\Models\DocumentoContrato;
use App\Databases\Models\FaseLicitacao;
use App\Databases\Models\Licitacao;
use App\Databases\Models\Notificacao;
use App\Databases\Models\TermoAditivo;
use App\Databases\Models\TipoDocumento;
use App\Databases\Models\TipoDocumentoContrato;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;
use function PHPUnit\Framework\isNull;

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
            } else {
                $params['situacao'] = 'NV';
            }
            $valorNumerico = preg_replace("/[^0-9]/", "", $params['valor']);

            $contrato = new Contrato([
                'numero' => $params['numero'],
                'fiscal'=>$params['fiscal'],
                'objeto'=>$params['objeto'],
                'cnpj' => $params['cnpj'],
                'empresa' => $params['empresa'],
                'oberservacao' => $params['observacao'],
                'licitacao_id'=>$params['licitacao_id'],
                'situacao' => $params['situacao'],
                'data_inicio'=>$params['data_inicio'],
                'data_fim'=>$params['data_fim'],
                'valor'=>$valorNumerico,
            ]);
            $contrato->save();

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
            foreach ( $all_documentos as $tipo_documento) {
                foreach ($params as $key => $file) {
                    if ($file instanceof UploadedFile) {
                        $key = str_replace('_', ' ', $key);
                        $path = $file->store('/public');
                        $documentoExistente = DocumentoContrato::where('nome', $key)
                            ->where('tipo', 'contrato')
                            ->where('tipo_id', $contrato->id)
                            ->first();
                        if ($documentoExistente != null) {
                            $documentoExistente->path = $path;
                            $documentoExistente->save();
                        } else {
                            $arquivo = new DocumentoContrato([
                                'nome' => $key,
                                'tipo' => 'contrato',
                                'tipo_id' => $contrato->id,
                                'path' => $path,
                                'contrato_id' => $contrato->id,
                            ]);
                            $arquivo->save();
                        }
                    }
                }
            }
            $this->verificaSituacao($contrato->id);
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
    public function getAll(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Contrato::query()
            ->with(['termo_aditivo'])
            ->selectRaw("
                CONTRATO.*,
                CASE
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.data_fim FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.data_fim FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.data_fim FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.data_fim FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '4')
                    ELSE contrato.DATA_FIM
                END AS data_fim_real ,
                CASE
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.VALOR FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.VALOR FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.VALOR FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.VALOR FROM scmgcc.TERMO_ADITIVO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.ordem = '4')
                    ELSE contrato.VALOR
                END AS valor_real
            ");

        return $query
            ->orderBy($params['sort'] ?? 'empresa', $params['sort_dir'] ?? 'asc')
            ->paginate( 10)
            ->appends($params);

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
