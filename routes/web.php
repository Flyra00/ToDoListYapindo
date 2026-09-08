<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('todos.index')
        : redirect()->route('login');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $todos = $request->user()->todos()->latest()->take(5)->get();
    $totalCount = $request->user()->todos()->count();
    $activeCount = $request->user()->todos()->where('completed', false)->count();
    $completedCount = $request->user()->todos()->where('completed', true)->count();

    return view('dashboard', [
        'todos' => $todos,
        'totalCount' => $totalCount,
        'activeCount' => $activeCount,
        'completedCount' => $completedCount,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::resource('todos', TodoController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
