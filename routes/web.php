<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

// Landing pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');

// UBAH: Hapus middleware dari route page, pindahkan ke API
Route::get('/quiz', [QuizController::class, 'showQuiz'])->name('quiz');
Route::get('/indramayu-example', [ContentController::class, 'showIndramayuExample'])->name('indramayu.example');

// API Routes for AJAX
Route::prefix('api')->group(function () {
    // Auth endpoints
    Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/auth/verify', [AuthController::class, 'verify'])->name('api.auth.verify');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');

    // Get majors and study programs
    Route::get('/majors', [AuthController::class, 'getMajors'])->name('api.majors');
    Route::get('/study-programs/{major_id}', [AuthController::class, 'getStudyPrograms'])->name('api.study-programs');

    // Protected routes
    Route::middleware('auth:visitor')->group(function () {
        // Content endpoints
        Route::get('/contents', [ContentController::class, 'index'])->name('api.contents.index');
        Route::get('/contents/{slug}', [ContentController::class, 'show'])->name('api.contents.show');

        // Resources endpoints
        Route::get('/resources', [ReferenceController::class, 'index'])->name('api.resources.index');
        Route::get('/resources/{content_slug}', [ReferenceController::class, 'byContent'])->name('api.resources.by-content');

        // Progress endpoints
        Route::get('/progress', [ProgressController::class, 'getUserProgress'])->name('api.progress.get');
        Route::post('/progress/toggle', [ProgressController::class, 'toggleProgress'])->name('api.progress.toggle');

        // Certificate endpoints
        Route::get('/certificate', [ProgressController::class, 'getCertificateData'])->name('api.certificate');

        // TAMBAH: API endpoints untuk quiz dengan middleware
        Route::get('/quiz/data', [QuizController::class, 'getQuizData'])->name('api.quiz.data');
        Route::post('/quiz/submit', [QuizController::class, 'submitQuiz'])->name('api.quiz.submit');
    });
});

// Individual pillar pages
Route::get('/pilar/{slug}', [ContentController::class, 'showPillar'])->name('pillar.show');

// References page
Route::get('/references', [ReferenceController::class, 'showReferences'])->name('references.show');

// Fallback for SPA
Route::fallback(function () {
    return redirect('/');
});
