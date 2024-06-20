<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pessoa extends Model
{
    use SoftDeletes;
    protected $table = 'pessoa';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'pessoa_id_seq';

}

