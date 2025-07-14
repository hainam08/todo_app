<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;

use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskReminderNotification;
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
Route::get('/test', function () {
    return view('test');
});
Route::get('/demo', function () {
    return view('demo');
});


Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/verify/{token}', [AuthController::class, 'verify']);
Route::post('/resend-verification', [VerificationController::class, 'resend'])->name('user.resend.verification');

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showUserLoginForm'])->name('user.login')->withoutMiddleware('guest:web');
    Route::post('/login', [LoginController::class, 'userLogin']);
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('user/dashboard', [TaskController::class, 'dash'])->name('user.dashboard');
    Route::get('user/index', [TaskController::class, 'index'])->name('user.index');
    Route::post('/tasks/{task}/toggle-reminder', [TaskController::class, 'toggleReminder'])->name('tasks.toggleReminder');
    Route::post('bulk-complete-tasks', [TaskController::class, 'bulkComplete'])->name('tasks.bulk-complete');
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::resource('tasks', TaskController::class);
});
Route::middleware('guest:admin')->prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'adminLogin']);
});
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');




Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('statistics', [AdminController::class, 'statistics'])->name('admin.statistics.index');
    Route::get('dashboard', [AdminTaskController::class, 'index'])->name('admin.dashboard');
    Route::get('tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
    Route::get('tasks/{id}/edit', [AdminTaskController::class, 'edit'])->name('admin.tasks.edit');
    Route::patch('tasks/{id}', [AdminTaskController::class, 'update'])->name('admin.tasks.update');
    Route::delete('tasks/{id}', [AdminTaskController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::patch('users/{id}/toggle-lock', [AdminUserController::class, 'toggleLock'])->name('admin.users.toggle-lock');
    Route::delete('users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/', function () {
        return view('home');
    });
});

Route::get('/test-mail', function () {
    $task = \App\Models\Task::find(34);
    Mail::to($task->user->email)->send(new \App\Mail\TaskReminderMail($task));
    return 'Gửi thử thành công!';
});
Route::get('/test-reminder', function () {
    dispatch(new \App\Jobs\SendTaskReminderEmail());
    return 'Đã dispatch job reminder!';
});


Route::get('/send-test-noti', function () {
    $user = User::first();
    $task = Task::first(); // hoặc tạo giả nếu chưa có task

    $user->notify(new TaskReminderNotification($task));

    return 'Sent!';
});

use App\Jobs\SendTaskReminderEmail;


Route::get('/test-send-mail-job', function () {
    SendTaskReminderEmail::dispatch();
    return 'Gửi job thành công!';
});
