<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebhookAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Defina seu token seguro aqui ou use .env
        $tokenEsperado = env('WEBHOOK_SECRET', 'sk_3j82fG9kXaW1qz7RpdLvbA5MNKcYxVt0');

        $tokenRecebido = $request->header('Authorization');

        if ($tokenRecebido !== 'Bearer ' . $tokenEsperado) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        return $next($request);
    }
}