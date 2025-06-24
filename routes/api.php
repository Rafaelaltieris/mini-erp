<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/webhook/pedido', [WebhookController::class, 'receber'])
     ->middleware('webhook.auth');


