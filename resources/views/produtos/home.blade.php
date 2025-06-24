@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center d-flex justify-content-between align-items-center">
        Montink
        <a href="{{ route('produtos.create') }}" class="btn btn-success btn-sm">
            + Adicionar Produto
        </a>
    </h1>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse ($produtos as $produto)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : asset('images/placeholder.avif') }}"
                    alt="{{ $produto->nome }}"
                    class="card-img-top"
                    style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $produto->nome }}</h5>
                    <p class="card-text mb-2">Preço base: <strong>R$ {{ number_format($produto->preco, 2, ',', '.') }}</strong></p>

                    <form action="{{ route('carrinho.adicionar') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">

                        {{-- Select de variações --}}
                        <label for="variacao_{{ $produto->id }}" class="form-label">Escolha a variação:</label>
                        <select name="variacao" id="variacao_{{ $produto->id }}" class="form-select mb-3" required>
                            <option value="" disabled selected>Selecione</option>
                            @foreach($produto->estoques as $estoque)
                            <option value="{{ $estoque->variacao }}">
                                {{ $estoque->variacao }} - Estoque: {{ $estoque->quantidade }}
                            </option>
                            @endforeach
                        </select>

                        <div class="input-group mb-3">
                            <input type="number" name="quantidade" value="1" min="1" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Comprar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Nenhum produto disponível no momento.</p>
        @endforelse

        @php
        $carrinho = session('carrinho', []);
        $quantidadeTotal = collect($carrinho)->sum(fn($item) => $item['quantidade']);
        @endphp

        <a href="{{ route('carrinho.index') }}"
            class="btn btn-primary position-fixed top-0 end-0 m-3 d-flex align-items-center shadow"
            style="z-index: 1050; border-radius: 50px; padding: 0.5rem 1rem; min-width: 120px;">

            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart-fill me-2" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
            </svg>

            Carrinho ({{ $quantidadeTotal }})
        </a>
    </div>
</div>
@endsection