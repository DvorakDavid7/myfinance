<?php

declare(strict_types=1);

use App\Http\Controllers\BankEmailWebhookController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/transaction', BankEmailWebhookController::class)->middleware('auth:sanctum');

Route::get('/token/n8n', function (Request $request) {
    $user = User::where('name', 'n8n')->first();

    if (! $user) {
        return ['error' => 'User n8n not found'];
    }

    if ($user->tokens()->count() > 0) {
        return ['token' => 'Token already exists'];
    }

    $token = $user->createToken('n8n-token');

    return ['token' => $token->plainTextToken];
});
