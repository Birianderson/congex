<?php

namespace App\Databases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class NotaFiscal extends Model
{
    use SoftDeletes;
    protected $table = 'nota_fiscal';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public string $sequence = 'nota_fiscal_id_seq';

    public function pagamentos()
    {
        return $this->belongsTo(Pagamento::class);
    }
}

