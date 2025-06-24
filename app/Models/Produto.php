<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'preco',
        'imagem'
    ];

    // Um produto pode ter várias variações de estoque
    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }
}
