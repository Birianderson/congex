<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pagamento extends Model
{
    use SoftDeletes;
    protected $table = 'pagamento';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'pagamento_id_seq';

    public function termo()
    {
        return $this->belongsTo(Termo::class);
    }
}

