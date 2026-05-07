<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| Web Routes - StudyRank F1
|--------------------------------------------------------------------------
*/

// Redireciona a página inicial para o login
Route::get('/', function () {
    return redirect('/login');
});

// Rotas de Autenticação (Login e Cadastro)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROTAS PROTEGIDAS (Só entra quem estiver logado) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal (O Paddock)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Sistema de Quizzes (Os GPs)
    // O {id} é dinâmico: /quiz/1, /quiz/2, etc.
    Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{id}/finish', [QuizController::class, 'finish'])->name('quiz.finish');

});