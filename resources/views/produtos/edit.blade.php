@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Produto</h1>
    <form action="{{ route('produtos.update', $produto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" required>
        </div>

        <h4>Variações e Estoque</h4>

        <div id="estoques-existentes">
            @foreach($produto->estoques as $index => $estoque)
            <div class="row mb-2">
                <input type="hidden" name="estoque_ids[]" value="{{ $estoque->id }}">
                <div class="col">
                    <input type="text" name="variacoes[]" class="form-control" value="{{ old('variacoes.' . $index, $estoque->variacao) }}" placeholder="Variação">
                </div>
                <div class="col">
                    <input type="number" name="quantidades[]" class="form-control" value="{{ old('quantidades.' . $index, $estoque->quantidade) }}" placeholder="Quantidade" min="0">
                </div>
            </div>
            @endforeach
        </div>

        <h5>Adicionar novas variações</h5>
        <div id="novas-variacoes-container"></div>

        <button type="button" id="add-nova-variacao" class="btn btn-secondary mb-3">Adicionar Nova Variação</button>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>
</div>

<script>
document.getElementById('add-nova-variacao').addEventListener('click', function() {
    const container = document.getElementById('novas-variacoes-container');
    const newRow = document.createElement('div');
    newRow.classList.add('row', 'mb-2');
    newRow.innerHTML = `
        <div class="col">
            <input type="text" name="novas_variacoes[]" class="form-control" placeholder="Variação (ex: Tamanho G)">
        </div>
        <div class="col">
            <input type="number" name="novas_quantidades[]" class="form-control" placeholder="Quantidade" min="0">
        </div>
    `;
    container.appendChild(newRow);
});
</script>
@endsection