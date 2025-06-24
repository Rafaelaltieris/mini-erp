<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use App\Models\Estoque;
use App\Models\PedidoProduto;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function finalizar(Request $request)
    {
        $request->validate([
            'cep' => 'required',
            'rua' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'uf' => 'required',
        ]);

        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        $subtotal = array_sum(array_map(fn($item) => $item['preco'] * $item['quantidade'], $carrinho));

        // Verifica se tem cupom na sessão
        $cupom = session('cupom');
        $desconto = 0;

        if ($cupom) {
            if ($cupom->eh_percentual) {
                $desconto = ($cupom->desconto / 100) * $subtotal;
            } else {
                $desconto = $cupom->desconto;
            }
        }

        // Aplica o desconto no subtotal (mas não pode ser menor que 0)
        $subtotalDescontado = max(0, $subtotal - $desconto);

        // Cálculo do frete com base no subtotal antes do desconto
        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200.00) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }

        // Total com desconto e frete
        $total = $subtotalDescontado + $frete;

        DB::beginTransaction();

        try {
            $pedido = Pedido::create([
                'subtotal' => $subtotal,
                'frete' => $frete,
                'total' => $total,
                'status' => 'PENDENTE',
                // 'cupom_id' => $cupom?->id, // Descomente se tiver esse campo na tabela `pedidos`
            ]);

            foreach ($carrinho as $item) {
                PedidoProduto::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'variacao' => $item['variacao'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                ]);

                $estoque = Estoque::where('produto_id', $item['produto_id'])
                    ->where('variacao', $item['variacao'])
                    ->first();

                if ($estoque) {
                    $estoque->quantidade -= $item['quantidade'];
                    $estoque->save();
                }
            }

            DB::commit();

            return view('pedidos.pedido_finalizado', compact('pedido'));
            
            session()->forget('carrinho');
            session()->forget('cupom');

            return redirect()->route('produtos.index')->with('success', 'Pedido finalizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('carrinho.index')->with('error', 'Erro ao finalizar pedido.');
        }
    }

    public function aplicarCupom(Request $request)
    {
        $request->validate([
            'cupom' => 'required|string'
        ]);

        $cupom = Cupom::where('codigo', strtoupper($request->cupom))->first();

        if (!$cupom) {
            return redirect()->back()->with('error', 'Cupom inválido.');
        }

        if ($cupom->valido_ate && $cupom->valido_ate->isPast()) {
            return redirect()->back()->with('error', 'Cupom expirado.');
        }

        $carrinho = session('carrinho', []);
        $subtotal = array_sum(array_map(fn($item) => $item['preco'] * $item['quantidade'], $carrinho));

        if ($cupom->valor_minimo && $subtotal < $cupom->valor_minimo) {
            return redirect()->back()->with('error', 'Cupom exige valor mínimo de R$' . number_format($cupom->valor_minimo, 2, ',', '.'));
        }

        // Salvar cupom na sessão
        session(['cupom' => $cupom]);

        return redirect()->back()->with('success', 'Cupom aplicado com sucesso!');
    }
}
