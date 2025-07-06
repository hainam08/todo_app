<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Route::get('/dash', function () {
//     return view('auth.dashboard');
// });
// Route::get('/sign_in', function () {
//     return view('auth.sign-in');
// });
// Route::get('/home', function () {
//     return view('home');
// });
// Route::get('/sign_up', function () {
//     return view('auth.sign-up');
// });


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:web'])->group(function () {
    Route::get('dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TaskController::class);
});

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('dashboard', [AdminTaskController::class, 'index'])->name('admin.dashboard');
    Route::get('tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('statistics', [AdminUserController::class, 'statistics'])->name('admin.statistics');
    Route::get('/', function () {
    return view('home');
});
});
