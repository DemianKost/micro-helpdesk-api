<?php

use Illuminate\Support\Facades\Route;
use Src\Domains\Ticket\Controllers\TicketCommentController;
use Src\Domains\Ticket\Controllers\TicketController;
use Src\Domains\User\Controllers\AuthController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('tickets')->group( function() {
        Route::get('/', [TicketController::class, 'index']);
        Route::get('/{id}', [TicketController::class, 'show']);
        Route::post('/', [TicketController::class, 'create']);
        Route::post('/{ticket}/assign', [TicketController::class, 'assign']);
        Route::put('/{ticket}', [TicketController::class, 'update']);
        Route::delete('/{ticket}', [TicketController::class, 'delete']);

        Route::prefix('comments')->group( function() {
            Route::post('/', [TicketCommentController::class, 'store']);
            Route::put('/{ticketComment}', [TicketCommentController::class, 'update']);
        });
    });

    
});