@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gerenciar Cupons</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('cupons.store') }}" class="mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="codigo" class="form-control" placeholder="Código" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="desconto" class="form-control" placeholder="Desconto" required>
            </div>
            <div class="col-md-2">
                <div class="form-check pt-2">
                    <input class="form-check-input" type="checkbox" name="eh_percentual" value="1" id="percentual">
                    <label class="form-check-label" for="percentual">É percentual?</label>
                </div>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="valor_minimo" class="form-control" placeholder="Valor mínimo">
            </div>
            <div class="col-md-3">
                <input type="date" name="validade" class="form-control">
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary">Cadastrar Cupom</button>
        </div>
    </form>

    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto</th>
                <th>Tipo</th>
                <th>Valor Mínimo</th>
                <th>Validade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cupons as $cupom)
                <tr>
                    <td>{{ $cupom->codigo }}</td>
                    <td>{{ $cupom->desconto_valor }}</td>
                    <td>{{ $cupom->desconto_percentual ? 'Percentual' : 'Fixo' }}</td>
                    <td>{{ $cupom->valor_minimo ?? '-' }}</td>  
                    <td>{{ $cupom->validade ? $cupom->validade->format('d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection