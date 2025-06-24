<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = [
        'produto_id',
        'variacao',
        'quantidade',
    ];

    // Estoque pertence a um Produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}