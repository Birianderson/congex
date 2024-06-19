<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'empresa_id_seq';

}

