<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Fiscal extends Model
{
    protected $table = 'fiscal';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'fiscal_id_seq';

}

