<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RiscoContrato extends Model
{
    use SoftDeletes;
    protected $table = 'risco_contrato';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'risco_contrato_id_seq';

    public function termo()
    {
        return $this->belongsTo(Termo::class);
    }
}

