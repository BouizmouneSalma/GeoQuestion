<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

// Routes d'authentification temporaires
Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/register', function () {
    return view('welcome');
})->name('register');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

// Page d'accueil
Route::get('/', [QuestionController::class, 'index']);

// Routes publiques pour voir les questions
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // IMPORTANT: Route create AVANT la route show avec paramètre
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
});

// Cette route doit venir APRÈS /questions/create
Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');

// Autres routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    
    // Routes pour les réponses
    Route::resource('answers', AnswerController::class)->except(['index', 'show']);
    
               Route::post('questions/{question}/answers', [AnswerController::class, 'store'])->name('questions.answers.store');
});

// Route pour afficher le layout (po
Route::get('/app', function () {
    return view('layouts.app');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');