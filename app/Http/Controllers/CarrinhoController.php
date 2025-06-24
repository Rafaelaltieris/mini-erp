<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;

class CarrinhoController extends Controller
{
    public function aplicarCupom(Request $request)
    {
        $codigo = $request->input('cupom');

        // Exemplo simples de buscar cupom pelo código no banco
        $cupom = \App\Models\Cupom::where('codigo', $codigo)->first();

        if (!$cupom) {
            return redirect()->back()->with('error', 'Cupom inválido.');
        }

        // Você pode validar outras regras, como data de validade, uso único, etc.

        // Salva o cupom na sessão para aplicar desconto na view
        session(['cupom' => $cupom]);

        return redirect()->back()->with('success', "Cupom '{$codigo}' aplicado com sucesso!");
    }
    
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

    public function index(Request $request)
    {
        $carrinho = session()->get('carrinho', []);
        $subtotal = array_sum(array_map(fn($item) => $item['preco'] * $item['quantidade'], $carrinho));

        // Frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.0;
        } elseif ($subtotal > 200) {
            $frete = 0.0;
        } else {
            $frete = 20.0;
        }

        // Pega cupom da sessão (se existir)
        $cupom = session('cupom');

        $desconto = 0;
        if ($cupom) {
            if ($cupom->desconto_percentual) {
                $desconto = $subtotal * ($cupom->desconto_valor / 100);
            } else {
                $desconto = $cupom->desconto_valor;
            }
        }

        // Total = subtotal + frete - desconto (sem ir para negativo)
        $total = max(0, $subtotal + $frete - $desconto);

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete', 'cupom', 'desconto', 'total'));
    }

    public function remover($key)
    {
        $carrinho = session('carrinho', []);

        if (!isset($carrinho[$key])) {
            return redirect()->back()->with('error', 'Item não encontrado no carrinho.');
        }

        unset($carrinho[$key]);

        session(['carrinho' => $carrinho]);

        return redirect()->back()->with('success', 'Item removido com sucesso!');
    }

    public function removerCupom()
    {
        session()->forget('cupom');
        return redirect()->back()->with('success', 'Cupom removido com sucesso!');
    }
}
