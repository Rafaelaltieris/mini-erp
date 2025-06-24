@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Produtos</h1>
    <a href="{{ route('produtos.create') }}" class="btn btn-primary mb-3">Novo Produto</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Variações / Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                <tr>
                    <td>{{ $produto->nome }}</td>
                    <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td>
                        <ul>
                            @foreach($produto->estoques as $estoque)
                            <li>{{ $estoque->variacao ?? 'Sem variação' }} — {{ $estoque->quantidade }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('carrinho.adicionar') }}" method="POST" class="d-flex mt-2">
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                            <select name="variacao" class="form-select form-select-sm me-2" required>
                                <option value="" disabled selected>Escolha a variação</option>
                                @foreach($produto->estoques as $estoque)
                                <option value="{{ $estoque->variacao }}">{{ $estoque->variacao }} ({{ $estoque->quantidade }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Comprar</button>
                        </form>
                    </td>
                </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>
@endsection