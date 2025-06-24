@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Produtos</h1>
    <a href="{{ route('produtos.create') }}" class="btn btn-primary mb-3">Novo Produto</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Variações / Estoque</th>
                    <th>Comprar</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                <tr>
                    <td>{{ $produto->nome }}</td>
                    <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td>
                        <ul class="mb-0" style="list-style: none; padding-left: 0;">
                            @foreach($produto->estoques as $estoque)
                            <li><strong>{{ $estoque->variacao ?? 'Sem variação' }}</strong> — {{ $estoque->quantidade }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form action="{{ route('carrinho.adicionar') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                            <select name="variacao" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Escolha</option>
                                @foreach($produto->estoques as $estoque)
                                <option value="{{ $estoque->variacao }}">
                                    {{ $estoque->variacao }} ({{ $estoque->quantidade }})
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Comprar</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection