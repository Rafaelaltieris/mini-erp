@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">🛒 Carrinho de Compras</h1>

    {{-- Mensagens --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($carrinho) === 0)
        <div class="alert alert-info text-center">Seu carrinho está vazio.</div>
    @else

        {{-- Cupom --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>🎟 Aplicar Cupom</h5>
                <form action="{{ route('carrinho.aplicarCupom') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="cupom" class="form-control" placeholder="Digite o código do cupom">
                    <button class="btn btn-outline-primary">Aplicar</button>
                </form>

                @if($cupom)
                <form action="{{ route('carrinho.removerCupom') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Remover cupom</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Produtos no carrinho --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Itens no Carrinho</h5>
                <div class="table-responsive">
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Variação</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Subtotal</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carrinho as $key => $item)
                            <tr>
                                <td>{{ $item['nome'] }}</td>
                                <td>{{ $item['variacao'] }}</td>
                                <td>{{ $item['quantidade'] }}</td>
                                <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('carrinho.remover', $key) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Remover</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Resumo --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Resumo do Pedido</h5>
                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>R$ {{ number_format($subtotal, 2, ',', '.') }}</strong>
                    </li>

                    @if($cupom)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Cupom <strong>{{ $cupom->codigo }}</strong> aplicado:</span>
                        <strong class="text-success">- R$ {{ number_format($desconto, 2, ',', '.') }}</strong>
                    </li>
                    @endif

                    <li class="list-group-item d-flex justify-content-between">
                        <span>Frete:</span>
                        <strong>R$ {{ number_format($frete, 2, ',', '.') }}</strong>
                    </li>

                    <li class="list-group-item d-flex justify-content-between fs-5">
                        <span>Total:</span>
                        <strong class="text-primary">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Endereço --}}
        <div class="card mb-5">
            <div class="card-body">
                <h5>Endereço de Entrega</h5>
                <form method="POST" action="{{ route('pedido.finalizar') }}" class="mt-3">
                    @csrf
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <label for="cep">CEP</label>
                            <input type="text" name="cep" id="cep" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="rua">Rua</label>
                            <input type="text" name="rua" id="rua" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="numero">Número</label>
                            <input type="text" name="numero" id="numero" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="bairro">Bairro</label>
                            <input type="text" name="bairro" id="bairro" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="cidade">Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="uf">UF</label>
                            <input type="text" name="uf" id="uf" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success btn-lg">Finalizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>

    @endif
</div>

{{-- ViaCEP --}}
<script>
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('rua').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('uf').value = data.uf;
                } else {
                    alert("CEP não encontrado.");
                }
            })
            .catch(() => {
                alert("Erro ao buscar o CEP.");
            });
    });
</script>
@endsection