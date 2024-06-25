<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Responsabilidade extends Model
{
    use SoftDeletes;
    protected $table = 'responsabilidade';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'pessoa_id_seq';

    public function contrato()
    {
        return $this->hasMany(Contrato::class);
    }
}

