<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotTelegramController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('setWebhook', [BotTelegramController::class, 'setWebhook']);
Route::post('demoarsysbot/webhook', [BotTelegramController::class, 'commandHandlerWebHook']);
Route::get('setDatabase',[BotTelegramController::class, 'setDatabase']);
Route::get('/send-message', [BotTelegramController::class, 'sendMessage']);

// Route::post('demoarsysbot/webhook', [ProposalController::class, 'commandHandlerWebHook']);
