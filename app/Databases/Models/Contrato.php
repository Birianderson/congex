<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Contrato extends Model
{
    use SoftDeletes;

    protected $table = 'contrato';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'contrato_id_seq';


    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function responsabilidades()
    {
        return $this->hasMany(Responsabilidade::class, 'contrato_id');
    }

    public function termo_aditivos()
    {
        return $this->hasMany(TermoAditivo::class, 'contrato_id')->orderBy('numero');
    }

}

