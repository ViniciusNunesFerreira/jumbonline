<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\V1\PDV\CustomerController;
use App\Http\Controllers\Api\V1\PDV\ProductController;
use App\Http\Controllers\Api\V1\PDV\PDVCashSessionController;
use App\Http\Controllers\Api\V1\PDV\AuthController as PDVAuthController;

use App\Http\Controllers\Api\V1\PDV\PDVOrderController;
use App\Http\Controllers\Api\V1\PDV\PDVPaymentController;
use App\Http\Controllers\Api\V1\Webhook\PDVMercadoPagoWebhookController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->group(function () {
    // =====================================================
    // PDV DESKTOP (ability:pdv:*)
    // =====================================================
    //->middleware('ability:pdv:*')
    Route::prefix('v1/pdv')->group(function () {
        
        // Busca de Filhos
        Route::prefix('filhos')->group(function () {
            Route::get('/', [CustomerController::class, 'index']);
            Route::get('/search', [CustomerController::class, 'search']);
            Route::get('/cpf/{identifier}', [CustomerController::class, 'showByCpf']);
        });
        
        // Produtos
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/check-stock', [ProductController::class, 'checkStock']);
        });
        
        // Categorias
        Route::get('/categories', [ProductController::class, 'categories']);

        Route::prefix('cash')->group(function () {
            Route::get('/status', [PDVCashSessionController::class, 'status']);
            Route::post('/open', [PDVCashSessionController::class, 'open']);
            Route::post('/movement', [PDVCashSessionController::class, 'movement']); // Sangria/Suprimento
            Route::post('/close', [PDVCashSessionController::class, 'close']);
        });

        // --- Pedidos ---
        Route::prefix('orders')->group(function () {
            Route::post('/', [PDVOrderController::class, 'store']);
        });

        // --- Pagamentos ---
        Route::prefix('payments')->group(function () {
            Route::post('/process', [PDVPaymentController::class, 'process']);
        });
        
        // Rota que o Front do PDV consulta a cada 3 segundos para ver se o PIX caiu
        //Route::get('/payment-intents/{paymentId}/check', [PDVPaymentController::class, 'checkStatus']);

        Route::prefix('payment-intents')->group(function () {
            // Cria a intenção (Gera o PIX)
            Route::post('/', [PDVPaymentController::class, 'process']);
            
            // Front-end checa de 3 em 3 segundos se o cliente já pagou
            Route::post('/{paymentId}/check', [PDVPaymentController::class, 'checkStatus']);
            
            // Se o operador clicar em "Cancelar PIX" na tela
            Route::delete('/{paymentId}', [PDVPaymentController::class, 'cancel']);
        });
      

    });
});


Route::prefix('v1/pdv/auth')->name('pdv.auth.')->group(function () {
    
    Route::post('/login', [PDVAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');
    
    // Rotas Protegidas do PDV
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::get('/me', [PDVAuthController::class, 'me'])->name('me');
        Route::post('/logout', [PDVAuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [PDVAuthController::class, 'refresh'])->name('refresh');
        Route::get('/validate', [PDVAuthController::class, 'validateToken'])->name('validate');
        
    });
});

Route::prefix('v1/webhooks')->group(function () {
    Route::post('/mercadopago', [PDVMercadoPagoWebhookController::class, 'handle']);
});




