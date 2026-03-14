<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\PollController as PublicPollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/polls', [PollController::class, 'index'])->name('polls');
    Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
    Route::get('/polls/{id}/results', [PollController::class, 'results'])->name('polls.results');
    Route::delete('/polls/{id}', [PollController::class, 'destroy'])->name('polls.destroy');
});

Route::get('poll/{slug}', [PublicPollController::class, 'index'])->name('public.poll');
Route::post('poll/{slug}/vote', [PublicPollController::class, 'vote'])->name('public.poll.vote');

require __DIR__.'/auth.php';
