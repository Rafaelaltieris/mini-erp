<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $table = 'cupons';
    protected $fillable = [
        'codigo',
        'desconto_valor',
        'desconto_percentual',
        'valor_minimo',
        'validade',
    ];

    protected $casts = [
        'validade' => 'date',
        'desconto_percentual' => 'boolean',
    ];
}
