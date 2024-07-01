<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Termo extends Model
{
    use SoftDeletes;
    protected $table = 'termo';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'termo_id_seq';

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }


    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'termo_id')->orderBy('numero');
    }
}

