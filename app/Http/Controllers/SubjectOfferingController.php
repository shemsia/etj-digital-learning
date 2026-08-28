<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectOffering;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Assessment;
use App\Models\Teacher;
class SubjectOfferingController extends Controller
{
    /**
     * Display subject offerings.
     */
    public function index()
    {
        $offerings = SubjectOffering::with([
            'subject',
            'semester',
            'modules.assessments',
    'teachers.user'
        ])->get();
$teachers = Teacher::with('user')->orderBy('id')->get();
        return view('admin.subject_offerings.index', compact('offerings'));
    }
    public function create()
{
    $subjects = Subject::orderBy('name')->get();
    $semesters = \App\Models\Semester::orderBy('id')->get();

    return view('admin.subject_offerings.create', compact(
        'subjects',
        'semesters'
    ));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'subject_id' => ['required', 'exists:subjects,id'],
        'semester_id' => ['required', 'exists:semesters,id'],
        'grade_level' => ['required', 'integer', 'min:1', 'max:12'],

        'modules' => ['required', 'array', 'min:1'],
        'modules.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
    ]);

    // Check if this subject offering already exists
    $existingOffering = SubjectOffering::where('subject_id', $validated['subject_id'])
        ->where('semester_id', $validated['semester_id'])
        ->where('grade_level', $validated['grade_level'])
        ->first();

    if ($existingOffering) {
        return back()
            ->withInput()
            ->withErrors([
                'subject_id' => 'This subject is already configured for this grade level and semester.',
            ]);
    }

    // Check total module weight
    $totalWeight = collect($validated['modules'])
        ->sum('weight');

    if (abs($totalWeight - 100) > 0.01) {
        return back()
            ->withInput()
            ->withErrors([
                'modules' => 'Module weights must total exactly 100%.',
            ]);
    }

    // Create subject offering
    $offering = SubjectOffering::create([
        'subject_id' => $validated['subject_id'],
        'semester_id' => $validated['semester_id'],
        'grade_level' => $validated['grade_level'],
    ]);

    // Create modules
    foreach ($validated['modules'] as $index => $moduleData) {
        $offering->modules()->create([
            'name' => 'Module ' . ($index + 1),
            'max_mark' => 100,
            'weight' => $moduleData['weight'],
            'order' => $index + 1,
        ]);
    }

    return redirect()
        ->route('admin.subject_offerings.index')
        ->with('success', 'Subject offering and modules created successfully.');
}
public function edit($id)
{
    $offering = SubjectOffering::with('modules')->findOrFail($id);

    $subjects = Subject::orderBy('name')->get();
    $semesters = \App\Models\Semester::orderBy('id')->get();

    return view('admin.subject_offerings.edit', compact(
        'offering',
        'subjects',
        'semesters'
    ));
}

public function update(Request $request, $id)
{
    $offering = SubjectOffering::with('modules')->findOrFail($id);

    $validated = $request->validate([
        'subject_id' => ['required', 'exists:subjects,id'],
        'semester_id' => ['required', 'exists:semesters,id'],
        'grade_level' => ['required', 'integer', 'min:1', 'max:12'],

        'modules' => ['required', 'array', 'min:1', 'max:20'],
        'modules.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check duplicate subject offering
    |--------------------------------------------------------------------------
    */

    $duplicate = SubjectOffering::where('subject_id', $validated['subject_id'])
        ->where('semester_id', $validated['semester_id'])
        ->where('grade_level', $validated['grade_level'])
        ->where('id', '!=', $offering->id)
        ->exists();

    if ($duplicate) {
        return back()
            ->withInput()
            ->withErrors([
                'subject_id' =>
                    'This subject is already configured for this grade level and semester.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Check module weights
    |--------------------------------------------------------------------------
    */

    $totalWeight = collect($validated['modules'])
        ->sum('weight');

    if (abs($totalWeight - 100) > 0.01) {
        return back()
            ->withInput()
            ->withErrors([
                'modules' =>
                    'Module weights must total exactly 100%.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update subject offering
    |--------------------------------------------------------------------------
    */

    $offering->update([
        'subject_id' => $validated['subject_id'],
        'semester_id' => $validated['semester_id'],
        'grade_level' => $validated['grade_level'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Existing modules
    |--------------------------------------------------------------------------
    */

    $existingModules = $offering->modules
        ->sortBy('order')
        ->values();

    $newModuleCount = count($validated['modules']);
    $oldModuleCount = $existingModules->count();

    /*
    |--------------------------------------------------------------------------
    | If reducing modules, check for assessments
    |--------------------------------------------------------------------------
    */

    if ($newModuleCount < $oldModuleCount) {

        $modulesToRemove = $existingModules
            ->slice($newModuleCount);

        foreach ($modulesToRemove as $module) {

            if ($module->assessments()->exists()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'modules' =>
                            $module->name .
                            ' cannot be removed because it already has assessments.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete unused modules
        |--------------------------------------------------------------------------
        */

        foreach ($modulesToRemove as $module) {
            $module->delete();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update existing modules and create new modules
    |--------------------------------------------------------------------------
    */

    foreach ($validated['modules'] as $index => $moduleData) {

        if (isset($existingModules[$index])) {

            $module = $existingModules[$index];

            $module->update([
                'name' => 'Module ' . ($index + 1),
                'weight' => $moduleData['weight'],
                'order' => $index + 1,
            ]);

        } else {

            $offering->modules()->create([
                'name' => 'Module ' . ($index + 1),
                'max_mark' => 100,
                'weight' => $moduleData['weight'],
                'order' => $index + 1,
            ]);
        }
    }

    return redirect()
        ->route('admin.subject_offerings.index')
        ->with(
            'success',
            'Subject offering and modules updated successfully.'
        );
}
public function destroy($id)
{
    $offering = SubjectOffering::with('modules')->findOrFail($id);

    // Check if any module has assessments
    foreach ($offering->modules as $module) {
        if ($module->assessments()->exists()) {
            return back()->withErrors([
                'delete' =>
                    'This subject offering cannot be deleted because one or more modules already have assessments.',
            ]);
        }
    }

    // Delete modules first
    foreach ($offering->modules as $module) {
        $module->delete();
    }

    // Delete subject offering
    $offering->delete();

    return redirect()
        ->route('admin.subject_offerings.index')
        ->with('success', 'Subject offering deleted successfully.');
}
public function assignTeacher(Request $request, $id)
{
    $offering = SubjectOffering::findOrFail($id);

    $validated = $request->validate([
        'teacher_id' => ['required', 'exists:teachers,id'],
    ]);

    $offering->teachers()->syncWithoutDetaching([
        $validated['teacher_id']
    ]);

    return back()->with(
        'success',
        'Teacher assigned to subject offering successfully.'
    );
}
}