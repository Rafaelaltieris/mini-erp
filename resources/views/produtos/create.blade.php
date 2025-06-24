@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Produto</h1>
    <form action="{{ route('produtos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
            @if($errors->has('nome'))
            <div class="text-danger mt-1">{{ $errors->first('nome') }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ old('preco') }}" required>
            @if($errors->has('preco'))
            <div class="text-danger mt-1">{{ $errors->first('preco') }}</div>
            @endif
        </div>


        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem do Produto</label>
            <input type="file" name="imagem" id="imagem" class="form-control" accept=".jpeg,.jpg,.png,.gif,.svg,.webp,image/*">
            <small class="form-text text-muted">Formatos aceitos: jpeg, jpg, png, gif, svg, webp</small>
            @if($errors->has('imagem'))
            <div class="text-danger mt-1">{{ $errors->first('imagem') }}</div>
            @endif
        </div>

        <h4>Variações e Estoque</h4>

        <div id="variacoes-container">
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="variacoes[]" class="form-control" placeholder="Variação (ex: Tamanho P)">
                </div>
                <div class="col">
                    <input type="number" name="quantidades[]" class="form-control" placeholder="Quantidade" min="0">
                </div>
            </div>
        </div>

        <button type="button" id="add-variacao" class="btn btn-secondary mb-3">Adicionar Variação</button>

        <button type="submit" class="btn btn-success">Salvar Produto</button>
    </form>
</div>

<script>
    document.getElementById('add-variacao').addEventListener('click', function() {
        const container = document.getElementById('variacoes-container');
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2');
        newRow.innerHTML = `
        <div class="col">
            <input type="text" name="variacoes[]" class="form-control" placeholder="Variação (ex: Tamanho M)">
        </div>
        <div class="col">
            <input type="number" name="quantidades[]" class="form-control" placeholder="Quantidade" min="0">
        </div>
    `;
        container.appendChild(newRow);
    });
</script>
@endsection