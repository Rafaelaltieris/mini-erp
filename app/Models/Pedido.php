<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'subtotal',
        'frete',
        'total',
        'status',
    ];

    // Aqui você pode criar relacionamento com produtos do pedido, 
    // mas como ainda não definimos essa tabela intermediária (pedido_produto), fica simples por enquanto.
    public function itens()
    {
        return $this->hasMany(PedidoProduto::class);
    }

    // Exemplo se tiver tabela intermediária:
    // public function produtos()
    // {
    //     return $this->belongsToMany(Produto::class, 'pedido_produto')
    //                 ->withPivot('quantidade', 'preco_unitario');
    // }
}
