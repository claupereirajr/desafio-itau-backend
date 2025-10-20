<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/transacao', [TransactionController::class, 'store']);
Route::delete('/transacao', [TransactionController::class, 'destroy']);
