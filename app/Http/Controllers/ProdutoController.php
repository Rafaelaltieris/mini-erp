<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;

class ProdutoController extends Controller
{
    public function home()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.home', compact('produtos'));
    }

    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'variacoes.*' => 'nullable|string|max:255',
            'quantidades.*' => 'nullable|integer|min:0',
            'imagem' => 'nullable|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp|max:2048',
        ]);

        $data = [
            'nome' => $request->nome,
            'preco' => $request->preco,
        ];

        // Se enviou imagem, salva no storage e adiciona no array $data
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = $path;
        }

        // Cria o produto com imagem (se tiver)
        $produto = Produto::create($data);

        // Cria as variações e estoques
        if ($request->variacoes && $request->quantidades) {
            foreach ($request->variacoes as $index => $variacao) {
                if (!empty($variacao)) {
                    Estoque::create([
                        'produto_id' => $produto->id,
                        'variacao' => $variacao,
                        'quantidade' => $request->quantidades[$index] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }


    public function edit(Produto $produto)
    {
        $produto->load('estoques');
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'variacoes.*' => 'nullable|string|max:255',
            'quantidades.*' => 'nullable|integer|min:0',
            'estoque_ids.*' => 'nullable|integer',
        ]);

        $produto->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        // Atualiza estoques existentes
        if ($request->estoque_ids) {
            foreach ($request->estoque_ids as $index => $estoque_id) {
                $estoque = Estoque::find($estoque_id);
                if ($estoque) {
                    $estoque->update([
                        'variacao' => $request->variacoes[$index] ?? $estoque->variacao,
                        'quantidade' => $request->quantidades[$index] ?? $estoque->quantidade,
                    ]);
                }
            }
        }

        // Adiciona novos estoques se houver
        if ($request->novas_variacoes && $request->novas_quantidades) {
            foreach ($request->novas_variacoes as $index => $nova_variacao) {
                if (!empty($nova_variacao)) {
                    Estoque::create([
                        'produto_id' => $produto->id,
                        'variacao' => $nova_variacao,
                        'quantidade' => $request->novas_quantidades[$index] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
