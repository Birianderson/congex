<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TipoArquivo extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_arquivo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'termo_id_seq';

    public function termo()
    {
        return $this->belongsTo(Termo::class);
    }


    public function empenho()
    {
        return $this->belongsTo(Empenho::class);
    }
}

