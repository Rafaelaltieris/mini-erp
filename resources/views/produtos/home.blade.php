@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Montink</h1>

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
    </div>
</div>
@endsection