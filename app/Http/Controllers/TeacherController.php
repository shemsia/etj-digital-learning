<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
public function dashboard()
{
    $teacher = Teacher::with(['subjects', 'classes'])
        ->where('user_id', auth()->id())
        ->firstOrFail();

    return view('teacher.dashboard', compact('teacher'));
}
public function manageClass($id)
{
    $teacher = Teacher::where('user_id', auth()->id())
        ->firstOrFail();

    $class = $teacher->classes()
        ->with('students.user')
        ->findOrFail($id);

    $activeSemester = \App\Models\Semester::where('is_active', true)
        ->first();

    return view('teacher.manage-class', compact(
        'teacher',
        'class',
        'activeSemester'
    ));
}
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

   public function marks(Request $request)
{
    $teacher = Teacher::with('subjects')
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
        ->orderBy('name')
        ->get();

    $students = collect();
    $existingMarks = collect();

    if ($request->filled('class_id')) {

        $students = Student::with('user')
            ->where('class_id', $request->class_id)
            ->orderBy('student_id')
            ->get();
    }

    if (
        $request->filled('class_id') &&
        $request->filled('subject_id') &&
        $request->filled('exam_type')
    ) {

        $studentIds = $students->pluck('id');

        $existingMarks = Mark::whereIn('student_id', $studentIds)
            ->where('subject_id', $request->subject_id)
            ->where('exam_type', $request->exam_type)
            ->get()
            ->keyBy('student_id');
    }

    return view('teacher.marks', compact(
        'teacher',
        'classes',
        'students',
        'existingMarks'
    ));
}
public function storeMarks(Request $request)
{
    $request->validate([
        'subject_id' => 'required|exists:subjects,id',
        'exam_type' => 'required|string|max:50',
        'marks' => 'required|array',
        'marks.*' => 'nullable|numeric|min:0|max:100',
    ]);

    $teacher = Teacher::where('user_id', auth()->id())
        ->firstOrFail();

    // Make sure the teacher is actually assigned this subject
    if (!$teacher->subjects()->where('subjects.id', $request->subject_id)->exists()) {
        abort(403, 'You are not assigned to this subject.');
    }

    foreach ($request->marks as $studentId => $score) {

        if ($score === null || $score === '') {
            continue;
        }

        Mark::updateOrCreate(
            [
                'student_id' => $studentId,
                'subject_id' => $request->subject_id,
                'exam_type' => $request->exam_type,
            ],
            [
                'teacher_id' => $teacher->id,
                'score' => $score,
            ]
        );
    }

    return redirect()
        ->back()
        ->with('success', 'Marks saved successfully.');
}
}