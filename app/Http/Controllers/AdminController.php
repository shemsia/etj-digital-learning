<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $semesters = Semester::orderBy('id')->get();

        return view('admin.dashboard', compact('semesters'));
    }

    public function activateSemester($id)
    {
        $semester = Semester::findOrFail($id);

        // Deactivate all semesters first
        Semester::query()->update([
            'is_active' => false,
        ]);

        // Activate the selected semester
        $semester->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                $semester->name . ' activated successfully.'
            );
    }
}