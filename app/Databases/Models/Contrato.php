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

    public function risco()
    {
        return $this->hasOne(RiscoContrato::class, 'contrato_id');
    }

    public function responsabilidades()
    {
        return $this->hasMany(Responsabilidade::class, 'contrato_id');
    }

    public function termos()
    {
        return $this->hasMany(Termo::class, 'contrato_id')->orderBy('numero');
    }

}

