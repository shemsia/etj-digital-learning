<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::orderByRaw('CAST(grade AS UNSIGNED)')
    ->orderBy('name')
    ->get();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required|in:9,10,11,12',
            'name' => 'required|string|max:50',
        ]);

        ClassModel::create([
            'grade' => $request->grade,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.classes.index');
    }
}