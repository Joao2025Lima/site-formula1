<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;

// Redireciona a página inicial para o login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de Autenticação (Públicas)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// Rotas Protegidas (Só entra quem estiver logado)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ROTA QUE ESTAVA FALTANDO:
    Route::post('/profile/photo', [AuthController::class, 'updatePhoto'])->name('profile.photo');

    // Sistema de Quizzes
    Route::controller(QuizController::class)->group(function () {
        Route::get('/quiz/{id}', 'show')->name('quiz.show');
        Route::post('/quiz/{id}/finish', 'finish')->name('quiz.finish');
    });
});