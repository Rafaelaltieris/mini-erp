<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CupomController;

Route::get('/', [ProdutoController::class, 'home'])->name('home');

Route::post('/pedido/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');

Route::resource('produtos', ProdutoController::class);

Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
// Route::post('/webhook/pedido', [WebhookController::class, 'receber'])->middleware('webhook.auth');


Route::get('/cupons', [CupomController::class, 'index'])->name('cupons.index');
Route::post('/cupons', [CupomController::class, 'store'])->name('cupons.store');
Route::post('/carrinho/cupom', [App\Http\Controllers\PedidoController::class, 'aplicarCupom'])->name('carrinho.aplicarCupom');


