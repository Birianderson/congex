<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Arquivo extends Model
{
    use SoftDeletes;
    protected $table = 'arquivo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'arquivo_id_seq';


    public function tipo_arquivo()
    {
        return $this->belongsTo(TipoArquivo::class, 'tipo_arquivo_id');
    }


    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function empenho()
    {
        return $this->belongsTo(Empenho::class);
    }


    public function nota_fiscal()
    {
        return $this->belongsTo(NotaFiscal::class);
    }

}

