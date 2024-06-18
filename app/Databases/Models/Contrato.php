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

}

