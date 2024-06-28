<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TermoAditivo extends Model
{
    use SoftDeletes;
    protected $table = 'termo_aditivo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'termo_aditivo_id_seq';

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }


}

