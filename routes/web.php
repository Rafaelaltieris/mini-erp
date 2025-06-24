use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CupomController;

Route::get('/', [ProdutoController::class, 'home'])->name('home');

Route::resource('produtos', ProdutoController::class);

// Pedido
Route::post('/pedido/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');

// Carrinho
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::delete('/carrinho/remover/{key}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');

// Cupom no carrinho
Route::post('/carrinho/aplicar-cupom', [CarrinhoController::class, 'aplicarCupom'])->name('carrinho.aplicarCupom');
Route::post('/carrinho/remover-cupom', [CarrinhoController::class, 'removerCupom'])->name('carrinho.removerCupom');

// Cupons (gerenciamento)
Route::resource('cupons', CupomController::class);

// Webhook Parte Extra
# Route::post('/webhook/pedido', [WebhookController::class, 'receber'])->middleware('webhook.auth');