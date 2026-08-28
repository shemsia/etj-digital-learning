<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController as TeacherDashboardController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentImportController;
use App\Http\Controllers\SubjectOfferingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =====================================================
// Profile
// =====================================================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


// =====================================================
// Authentication
// =====================================================

require __DIR__.'/auth.php';


// =====================================================
// Admin Dashboard
// =====================================================

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::post('/admin/semester/{id}/activate', [AdminController::class, 'activateSemester'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.semester.activate');


// =====================================================
// Teacher Dashboard
// =====================================================

Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'dashboard'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.dashboard');

Route::get('/teacher/class/{id}', [TeacherDashboardController::class, 'manageClass'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.class.manage');


// =====================================================
// Teacher Attendance
// =====================================================

Route::get('/teacher/class/{classId}/attendance', [TeacherAttendanceController::class, 'index'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.attendance.index');

Route::post('/teacher/class/{classId}/attendance', [TeacherAttendanceController::class, 'store'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.attendance.store');

Route::get('/teacher/attendance/{classId}/report', [TeacherAttendanceController::class, 'report'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.attendance.report');

Route::get('/teacher/attendance/{classId}/report/pdf', [TeacherAttendanceController::class, 'reportPdf'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.attendance.report.pdf');


// =====================================================
// Teacher Marks
// =====================================================

Route::get('/teacher/marks', [TeacherDashboardController::class, 'marks'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.marks');

Route::post('/teacher/marks', [TeacherDashboardController::class, 'storeMarks'])
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.marks.store');


// =====================================================
// Student Dashboard
// =====================================================

Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
    ->middleware(['auth', 'role:student'])
    ->name('student.dashboard');


// =====================================================
// ADMIN ROUTES
// =====================================================

Route::middleware(['auth', 'role:admin'])->group(function () {


    // =================================================
    // Classes
    // =================================================

    Route::get('/admin/classes', [ClassController::class, 'index'])
        ->name('admin.classes.index');

    Route::get('/admin/classes/create', [ClassController::class, 'create'])
        ->name('admin.classes.create');

    Route::post('/admin/classes', [ClassController::class, 'store'])
        ->name('admin.classes.store');


    // =================================================
    // Subjects
    // =================================================

    Route::get('/admin/subjects', [SubjectController::class, 'index'])
        ->name('admin.subjects.index');

    Route::get('/admin/subjects/create', [SubjectController::class, 'create'])
        ->name('admin.subjects.create');

    Route::post('/admin/subjects', [SubjectController::class, 'store'])
        ->name('admin.subjects.store');


    // =================================================
    // Teachers
    // =================================================

    Route::get('/admin/teachers', [TeacherController::class, 'index'])
        ->name('admin.teachers.index');

    Route::get('/admin/teachers/create', [TeacherController::class, 'create'])
        ->name('admin.teachers.create');

    Route::post('/admin/teachers', [TeacherController::class, 'store'])
        ->name('admin.teachers.store');


    // =================================================
    // Assign Subjects to Teacher
    // =================================================

    Route::get('/admin/teachers/{id}/subjects', [TeacherController::class, 'editSubjects'])
        ->name('admin.teachers.subjects');

    Route::put('/admin/teachers/{id}/subjects', [TeacherController::class, 'updateSubjects'])
        ->name('admin.teachers.subjects.update');


    // =================================================
    // Student Excel Import
    // =================================================

    Route::get('/admin/students/import', [StudentImportController::class, 'create'])
        ->name('admin.students.import');

    Route::post('/admin/students/import', [StudentImportController::class, 'store'])
        ->name('admin.students.import.store');

    Route::get('/admin/students/import/template', [StudentImportController::class, 'downloadTemplate'])
        ->name('admin.students.import.template');


    // =================================================
    // Students
    // =================================================

    Route::get('/admin/students', [AdminStudentController::class, 'index'])
        ->name('admin.students.index');

    Route::get('/admin/students/create', [AdminStudentController::class, 'create'])
        ->name('admin.students.create');

    Route::post('/admin/students', [AdminStudentController::class, 'store'])
        ->name('admin.students.store');

    Route::get('/admin/students/{id}/edit', [AdminStudentController::class, 'edit'])
        ->name('admin.students.edit');

    Route::put('/admin/students/{id}', [AdminStudentController::class, 'update'])
        ->name('admin.students.update');

    Route::delete('/admin/students/{id}', [AdminStudentController::class, 'destroy'])
        ->name('admin.students.destroy');

    Route::post('/admin/students/{id}/reset-password', [AdminStudentController::class, 'resetPassword'])
        ->name('admin.students.reset-password');


    // =================================================
    // Subject Offerings
    // =================================================

    Route::get('/admin/subject-offerings', [SubjectOfferingController::class, 'index'])
        ->name('admin.subject_offerings.index');

    Route::get('/admin/subject-offerings/create', [SubjectOfferingController::class, 'create'])
        ->name('admin.subject_offerings.create');

    Route::post('/admin/subject-offerings', [SubjectOfferingController::class, 'store'])
        ->name('admin.subject_offerings.store');
Route::post('/admin/subject-offerings/{id}/assign-teacher', [SubjectOfferingController::class, 'assignTeacher'])
    ->name('admin.subject_offerings.assign-teacher');
    Route::get('/admin/subject-offerings/{id}/edit', [SubjectOfferingController::class, 'edit'])
        ->name('admin.subject_offerings.edit');

    Route::put('/admin/subject-offerings/{id}', [SubjectOfferingController::class, 'update'])
        ->name('admin.subject_offerings.update');
        Route::delete('/admin/subject-offerings/{id}', [SubjectOfferingController::class, 'destroy'])
    ->name('admin.subject_offerings.destroy');

});