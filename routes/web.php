<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
use App\Http\Controllers\AdminController;
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'role:admin']);
        use App\Http\Controllers\TeacherController;
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
    ->middleware(['auth', 'role:teacher']);
       use App\Http\Controllers\StudentController;
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
    ->middleware(['auth', 'role:student']);