<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class WebhookController extends Controller
{
    public function receber(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string',
        ]);

        $pedido = Pedido::find($request->id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        if (strtolower($request->status) === 'cancelado') {
            $pedido->delete();
            return response()->json(['message' => 'Pedido cancelado e removido com sucesso']);
        } else {
            $pedido->update(['status' => strtoupper($request->status)]);
            return response()->json(['message' => 'Status do pedido atualizado']);
        }
    }
}