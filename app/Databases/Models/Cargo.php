<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cargo extends Model
{

    use SoftDeletes;
    protected $table = 'cargo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'cargo_id_seq';
}


