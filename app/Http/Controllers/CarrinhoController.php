<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;

class CarrinhoController extends Controller
{
    public function adicionar(Request $request)
    {
        $produtoId = $request->produto_id;
        $variacao = $request->variacao;

        $estoque = Estoque::where('produto_id', $produtoId)
                          ->where('variacao', $variacao)
                          ->first();

        if (!$estoque || $estoque->quantidade <= 0) {
            return back()->with('error', 'Produto ou variação fora de estoque.');
        }

        $produto = Produto::findOrFail($produtoId);

        $carrinho = session()->get('carrinho', []);

        $key = $produtoId . '_' . $variacao;

        if (isset($carrinho[$key])) {
            if ($carrinho[$key]['quantidade'] < $estoque->quantidade) {
                $carrinho[$key]['quantidade'] += 1;
            } else {
                return back()->with('error', 'Limite de estoque atingido para essa variação.');
            }
        } else {
            $carrinho[$key] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'variacao' => $variacao,
                'quantidade' => 1,
                'preco' => $produto->preco,
                'estoque_max' => $estoque->quantidade
            ];
        }

        session()->put('carrinho', $carrinho);

        return redirect()->route('carrinho.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        $subtotal = array_sum(array_map(fn($item) => $item['preco'] * $item['quantidade'], $carrinho));

        // Regras de frete
        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200.00) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }

        $total = $subtotal + $frete;

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete', 'total'));
    }
}