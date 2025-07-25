<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = Cupom::orderBy('validade', 'desc')->get();
        return view('cupons.index', compact('cupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons,codigo',
            'desconto_valor' => 'required|numeric|min:0',
            'desconto_percentual' => 'nullable|boolean',
            'valor_minimo' => 'nullable|numeric|min:0',
            'validade' => 'nullable|date',
        ]);

        Cupom::create([
            'codigo' => strtoupper($request->codigo),
            'desconto_valor' => $request->desconto_valor,
            'desconto_percentual' => $request->desconto_percentual ?? false,
            'valor_minimo' => $request->valor_minimo,
            'validade' => $request->validade,
        ]);

        return redirect()->route('cupons.index')->with('success', 'Cupom cadastrado com sucesso!');
    }

    public function edit(Cupom $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    public function update(Request $request, Cupom $cupom)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons,codigo,' . $cupom->id,
            'desconto_valor' => 'required|numeric|min:0',
            'eh_percentual' => 'nullable|boolean',
            'valor_minimo' => 'nullable|numeric|min:0',
            'validade' => 'nullable|date',
        ]);

        $cupom->update([
            'codigo' => strtoupper($request->codigo),
            'desconto_valor' => $request->desconto_valor,
            'desconto_percentual' => $request->has('eh_percentual'),
            'valor_minimo' => $request->valor_minimo,
            'validade' => $request->validade,
        ]);

        return redirect()->route('cupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cupom = \App\Models\Cupom::findOrFail($id);
        $cupom->delete();

        return redirect()->route('cupons.index')->with('success', 'Cupom removido com sucesso!');
    }
}