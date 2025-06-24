<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $table = 'pedido_produto';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'variacao',
        'quantidade',
        'preco_unitario',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}