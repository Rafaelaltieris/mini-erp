@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Cupom: {{ $cupom->codigo }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cupons.update', $cupom->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" name="codigo" id="codigo" class="form-control" 
                       value="{{ old('codigo', $cupom->codigo) }}" required>
            </div>
            <div class="col-md-2">
                <label for="desconto_valor" class="form-label">Desconto</label>
                <input type="number" step="0.01" name="desconto_valor" id="desconto_valor" class="form-control" 
                       value="{{ old('desconto_valor', $cupom->desconto_valor) }}" required>
            </div>
            <div class="col-md-2">
                <div class="form-check pt-4">
                    <input class="form-check-input" type="checkbox" name="eh_percentual" value="1" id="percentual"
                        {{ old('eh_percentual', $cupom->desconto_percentual) ? 'checked' : '' }}>
                    <label class="form-check-label" for="percentual">É percentual?</label>
                </div>
            </div>
            <div class="col-md-2">
                <label for="valor_minimo" class="form-label">Valor mínimo</label>
                <input type="number" step="0.01" name="valor_minimo" id="valor_minimo" class="form-control"
                       value="{{ old('valor_minimo', $cupom->valor_minimo) }}">
            </div>
            <div class="col-md-3">
                <label for="validade" class="form-label">Validade</label>
                <input type="date" name="validade" id="validade" class="form-control"
                       value="{{ old('validade', $cupom->validade ? $cupom->validade->format('Y-m-d') : '') }}">
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary">Salvar Alterações</button>
            <a href="{{ route('cupons.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
</div>
@endsection