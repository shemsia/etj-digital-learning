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
    use App\Http\Controllers\Admin\ClassController;

Route::get('/admin/classes', [ClassController::class, 'index'])
    ->name('admin.classes.index');

Route::get('/admin/classes/create', [ClassController::class, 'create'])
    ->name('admin.classes.create');

Route::post('/admin/classes', [ClassController::class, 'store'])
    ->name('admin.classes.store');
        use App\Http\Controllers\TeacherController as TeacherDashboardController;
        use App\Http\Controllers\admin\TeacherController;
Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'dashboard'])
    ->middleware(['auth', 'role:teacher']);
       use App\Http\Controllers\StudentController;
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
    ->middleware(['auth', 'role:student']);
    use App\Http\Controllers\Admin\SubjectController;

Route::get('/admin/subjects', [SubjectController::class, 'index'])
    ->name('admin.subjects.index');

Route::get('/admin/subjects/create', [SubjectController::class, 'create'])
    ->name('admin.subjects.create');

Route::post('/admin/subjects', [SubjectController::class, 'store'])
    ->name('admin.subjects.store');
    

Route::get('/admin/teachers', [TeacherController::class, 'index'])
    ->name('admin.teachers.index');

Route::get('/admin/teachers/create', [TeacherController::class, 'create'])
    ->name('admin.teachers.create');

Route::post('/admin/teachers', [TeacherController::class, 'store'])
    ->name('admin.teachers.store');