<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
   public function index(Request $request)
{
    $query = Student::with(['user', 'class']);

    // Search by student name or student ID
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    // Filter by grade
    if ($request->filled('grade')) {
        $query->whereHas('class', function ($q) use ($request) {
            $q->where('grade', $request->grade);
        });
    }

    // Filter by section
    if ($request->filled('section')) {
        $query->whereHas('class', function ($q) use ($request) {
            $q->where('name', $request->section);
        });
    }

    $students = $query->latest()->get();

    $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
        ->orderBy('name')
        ->get();

    return view('admin.students.index', compact('students', 'classes'));
}
    public function create()
    {
        $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
            ->orderBy('name')
            ->get();

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'student_id' => 'required|string|unique:students,student_id',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:Male,Female',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'gender' => $request->gender,
        ]);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit($id)
{
    $student = Student::with('user')->findOrFail($id);

    $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
        ->orderBy('name')
        ->get();

    return view('admin.students.edit', compact('student', 'classes'));
}
public function update(Request $request, $id)
{
    $student = Student::with('user')->findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'student_id' => 'required|string|unique:students,student_id,' . $student->id,
        'email' => 'required|email|unique:users,email,' . $student->user->id,
        'class_id' => 'required|exists:classes,id',
        'gender' => 'required|in:Male,Female',
    ]);

    $student->user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    $student->update([
        'student_id' => $request->student_id,
        'class_id' => $request->class_id,
        'gender' => $request->gender,
    ]);

    return redirect()
        ->route('admin.students.index')
        ->with('success', 'Student updated successfully.');
}
public function destroy($id)
{
    $student = Student::findOrFail($id);

    $user = $student->user;

    $student->delete();

    if ($user) {
        $user->delete();
    }

    return redirect()
        ->route('admin.students.index')
        ->with('success', 'Student deleted successfully.');
}

public function resetPassword($id)
{
    $student = Student::with('user')->findOrFail($id);

    $student->user->update([
        'password' => Hash::make('Student@123'),
    ]);

    return redirect()
        ->route('admin.students.index')
        ->with(
            'success',
            "Password reset successfully for {$student->user->name}. New password: Student@123"
        );
}

}