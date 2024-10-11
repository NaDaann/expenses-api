<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpensesController;

// ------------------- User Routes -------------------
/**
 * Register a new user
 * POST /api/user/register
 * 
 * Login an existing user
 * POST /api/user/login
 * 
 * As rotas de usuário são públicas, ou seja, não precisam de autenticação.
 * Não foi possível usar apiResource para criar as rotas de usuário, pois não temos um modelo de usuário definido. O controller não pode ser padronizado como o apiResource necessita.
 */
Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);
// ------------------- User Routes -------------------


// ------------------- Expenses Routes -------------------
Route::get('expenses/all', [ExpensesController::class, 'showAll'])->middleware('auth:sanctum');

Route::apiResource('expenses', ExpensesController::class)->except('index')->middleware('auth:sanctum');
// ------------------- Expenses Routes -------------------