<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'employee_id' => 'required|unique:teachers',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.teachers.index');
    }

    public function editSubjects($id)
{
    $teacher = Teacher::with(['subjects', 'classes'])->findOrFail($id);

    $subjects = Subject::all();

    $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
        ->orderBy('name')
        ->get();

    return view('admin.teachers.subjects', compact(
        'teacher',
        'subjects',
        'classes'
    ));
}
public function updateSubjects(Request $request, $id)
{
    $teacher = Teacher::findOrFail($id);

    $request->validate([
        'subjects' => 'nullable|array',
        'subjects.*' => 'exists:subjects,id',

        'classes' => 'nullable|array',
        'classes.*' => 'exists:classes,id',
    ]);

    // Assign subjects
    $teacher->subjects()->sync($request->subjects ?? []);

    // Assign classes/sections
    $teacher->classes()->sync($request->classes ?? []);

    return redirect()
        ->route('admin.teachers.index')
        ->with('success', 'Teacher subjects and classes updated successfully.');
}
}