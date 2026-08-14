<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportController extends Controller
{
    public function create()
    {
        return view('admin.students.import');
    }
public function downloadTemplate()
{
    return Excel::download(
        new \App\Exports\StudentsTemplateExport,
        'students_template.xlsx'
    );
}
   public function store(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
    ]);

    $import = new StudentsImport();

    Excel::import($import, $request->file('file'));

    $message = "{$import->imported} student(s) imported successfully.";

    if (count($import->skipped) > 0) {
        $message .= " " . count($import->skipped) . " row(s) skipped.";

        foreach ($import->skipped as $skipped) {
            $message .= " Row {$skipped['row']}: {$skipped['reason']}";
        }
    }

    return redirect()
        ->route('admin.students.index')
        ->with('success', $message);
}
}