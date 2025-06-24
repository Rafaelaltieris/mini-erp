@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1>🎉 Pedido Finalizado com Sucesso!</h1>
    <p>Obrigado pela compra! Seu pedido número <strong>#{{ $pedido->id }}</strong> foi recebido.</p>

    <h4>Resumo do Pedido</h4>
    <p><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ $pedido->status }}</p>

    <a href="{{ route('produtos.home') }}" class="btn btn-primary mt-3">Continuar Comprando</a>
</div>
@endsection