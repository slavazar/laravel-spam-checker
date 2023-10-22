<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Account\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/comments', [CommentController::class, 'index'])->name('account.comments.index');
    Route::get('/comments/add', [CommentController::class, 'create'])->name('account.comments.add');
    Route::post('/comments/add', [CommentController::class, 'store']);
    Route::get('/comments/edit/{id}', [CommentController::class, 'edit'])->name('account.comments.edit');
    Route::post('/comments/edit/{id}', [CommentController::class, 'update']);
    Route::get('/comments/delete/{id}', [CommentController::class, 'destroy'])->name('account.comments.delete');
    
});

require __DIR__.'/auth.php';
