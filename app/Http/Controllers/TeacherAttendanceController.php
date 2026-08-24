<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
 public function index(Request $request, $classId)
{
    $teacher = Teacher::where('user_id', auth()->id())
        ->firstOrFail();

    $class = $teacher->classes()
        ->with('students.user')
        ->findOrFail($classId);

    $semester = Semester::where('is_active', true)->first();

    if (!$semester) {
        abort(403, 'No semester is currently active.');
    }

    $date = $request->input(
        'date',
        now()->toDateString()
    );

    $attendances = Attendance::where('class_id', $class->id)
        ->where('semester_id', $semester->id)
        ->where('date', $date)
        ->get()
        ->keyBy('student_id');

    return view('teacher.attendance.index', compact(
        'teacher',
        'class',
        'semester',
        'date',
        'attendances'
    ));
}

    public function store(Request $request, $classId)
    {
        $teacher = Teacher::where('user_id', auth()->id())
            ->firstOrFail();

        $class = $teacher->classes()
            ->findOrFail($classId);

        $semester = Semester::where('is_active', true)->first();

        if (!$semester) {
            abort(403, 'No semester is currently active.');
        }

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:Present,Absent,Late',
        ]);

        foreach ($request->attendance as $studentId => $status) {

            $studentBelongsToClass = $class->students()
                ->where('id', $studentId)
                ->exists();

            if (!$studentBelongsToClass) {
                abort(403, 'Invalid student.');
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'semester_id' => $semester->id,
                    'date' => $request->date,
                ],
                [
                    'class_id' => $class->id,
                    'status' => $status,
                ]
            );
        }

        return redirect()
            ->route('teacher.attendance.index', [
                'classId' => $class->id,
                'date' => $request->date,
            ])
            ->with('success', 'Attendance saved successfully.');
    }
public function reportPdf(Request $request, $classId)
{
    $teacher = Teacher::where('user_id', auth()->id())
        ->firstOrFail();

    $class = $teacher->classes()
        ->with('students.user')
        ->findOrFail($classId);

    $semester = Semester::where('is_active', true)->first();

    if (!$semester) {
        abort(403, 'No semester is currently active.');
    }

    /*
    |--------------------------------------------------------------------------
    | Selected month
    |--------------------------------------------------------------------------
    */

    $selectedMonth = $request->input(
        'month',
        now()->format('Y-m')
    );

    try {
        $selectedDate = \Carbon\Carbon::createFromFormat(
            'Y-m',
            $selectedMonth
        )->startOfMonth();
    } catch (\Exception $e) {
        $selectedDate = now()->startOfMonth();
        $selectedMonth = $selectedDate->format('Y-m');
    }

    /*
    |--------------------------------------------------------------------------
    | Find the first attendance recorded in this semester
    |--------------------------------------------------------------------------
    */

    $firstAttendance = Attendance::where('class_id', $class->id)
        ->where('semester_id', $semester->id)
        ->orderBy('date', 'asc')
        ->first();

    /*
    | No attendance yet
    */

    if (!$firstAttendance) {
        abort(404, 'No attendance records found for this semester.');
    }

    $firstMonth = \Carbon\Carbon::parse(
        $firstAttendance->date
    )->startOfMonth();

    /*
    |--------------------------------------------------------------------------
    | Do not generate months after today
    |--------------------------------------------------------------------------
    */

    $lastAllowedMonth = $selectedDate->copy();

    if ($lastAllowedMonth->greaterThan(now()->startOfMonth())) {
        $lastAllowedMonth = now()->startOfMonth();
    }

    /*
    |--------------------------------------------------------------------------
    | Build monthly reports
    |--------------------------------------------------------------------------
    */

    $monthlyReports = [];

    $currentMonth = $firstMonth->copy();

    while ($currentMonth->lessThanOrEqualTo($lastAllowedMonth)) {

        $monthStart = $currentMonth->copy()->startOfMonth();

        $monthEnd = $currentMonth->copy()->endOfMonth();

        /*
        | If this is the current month, don't show future days.
        */

        if ($monthEnd->greaterThan(now())) {
            $monthEnd = now()->copy();
        }

        $attendances = Attendance::where('class_id', $class->id)
            ->where('semester_id', $semester->id)
            ->whereBetween('date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])
            ->orderBy('date')
            ->get();

        $attendanceByStudent = $attendances->groupBy('student_id');

        /*
        |--------------------------------------------------------------------------
        | Monthly student summaries
        |--------------------------------------------------------------------------
        */

        $monthlySummaries = [];

        foreach ($class->students as $student) {

            $records = $attendanceByStudent->get(
                $student->id,
                collect()
            );

            $present = $records
                ->where('status', 'Present')
                ->count();

            $absent = $records
                ->where('status', 'Absent')
                ->count();

            $late = $records
                ->where('status', 'Late')
                ->count();

            $total = $records->count();

            $attended = $present + $late;

            $percentage = $total > 0
                ? round(($attended / $total) * 100, 2)
                : 0;

            $monthlySummaries[$student->id] = [
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'total' => $total,
                'percentage' => $percentage,
            ];
        }

        $monthlyReports[] = [
            'date' => $currentMonth->copy(),
            'attendances' => $attendances,
            'attendanceByStudent' => $attendanceByStudent,
            'studentSummaries' => $monthlySummaries,
        ];

        /*
        | Move to next month
        */

        $currentMonth->addMonth();
    }

    /*
    |--------------------------------------------------------------------------
    | CUMULATIVE SUMMARY
    |--------------------------------------------------------------------------
    |
    | This includes attendance from the first attendance month through
    | the selected month only.
    |
    */

    $summaryEndDate = $lastAllowedMonth
        ->copy()
        ->endOfMonth();

    if ($summaryEndDate->greaterThan(now())) {
        $summaryEndDate = now()->copy();
    }

    $allAttendances = Attendance::where('class_id', $class->id)
        ->where('semester_id', $semester->id)
        ->whereBetween('date', [
            $firstMonth->toDateString(),
            $summaryEndDate->toDateString(),
        ])
        ->orderBy('date')
        ->get();

    $allAttendanceByStudent = $allAttendances->groupBy('student_id');

    $studentSummaries = [];

    foreach ($class->students as $student) {

        $records = $allAttendanceByStudent->get(
            $student->id,
            collect()
        );

        $present = $records
            ->where('status', 'Present')
            ->count();

        $absent = $records
            ->where('status', 'Absent')
            ->count();

        $late = $records
            ->where('status', 'Late')
            ->count();

        $total = $records->count();

        $attended = $present + $late;

        $percentage = $total > 0
            ? round(($attended / $total) * 100, 2)
            : 0;

        $studentSummaries[$student->id] = [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'percentage' => $percentage,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'teacher.attendance.pdf',
        compact(
            'teacher',
            'class',
            'semester',
            'monthlyReports',
            'studentSummaries',
            'firstMonth',
            'lastAllowedMonth'
        )
    );

    $pdf->setPaper('a4', 'landscape');

    $filename =
        'attendance-report-' .
        $class->grade . '-' .
        $class->name . '-' .
        $lastAllowedMonth->format('Y-m') .
        '.pdf';

    return $pdf->download($filename);
}
public function report(Request $request, $classId)
{
    $teacher = Teacher::where('user_id', auth()->id())
        ->firstOrFail();

    $class = $teacher->classes()
        ->with('students.user')
        ->findOrFail($classId);

    $semester = Semester::where('is_active', true)->first();

    if (!$semester) {
        abort(403, 'No semester is currently active.');
    }

    /*
    |--------------------------------------------------------------------------
    | Selected month
    |--------------------------------------------------------------------------
    */

    $selectedMonth = $request->input(
        'month',
        now()->format('Y-m')
    );

    try {
        $monthDate = Carbon::createFromFormat(
            'Y-m',
            $selectedMonth
        )->startOfMonth();
    } catch (\Exception $e) {
        $monthDate = now()->startOfMonth();
        $selectedMonth = $monthDate->format('Y-m');
    }

    /*
    |--------------------------------------------------------------------------
    | Month navigation
    |--------------------------------------------------------------------------
    */

    $previousMonth = $monthDate
        ->copy()
        ->subMonth()
        ->format('Y-m');

    $nextMonth = $monthDate
        ->copy()
        ->addMonth()
        ->format('Y-m');

    /*
    |--------------------------------------------------------------------------
    | Prevent navigation/report from going into the future
    |--------------------------------------------------------------------------
    */

    $currentMonth = now()->startOfMonth();

    if ($monthDate->greaterThan($currentMonth)) {
        $monthDate = $currentMonth;
        $selectedMonth = $monthDate->format('Y-m');

        $previousMonth = $monthDate
            ->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $monthDate
            ->copy()
            ->addMonth()
            ->format('Y-m');
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance for selected month
    |--------------------------------------------------------------------------
    */

    $startOfMonth = $monthDate->copy()->startOfMonth();

    $endOfMonth = $monthDate->copy()->endOfMonth();

    if ($endOfMonth->greaterThan(now())) {
        $endOfMonth = now()->copy();
    }

    $attendances = Attendance::where('class_id', $class->id)
        ->where('semester_id', $semester->id)
        ->whereBetween('date', [
            $startOfMonth->toDateString(),
            $endOfMonth->toDateString(),
        ])
        ->orderBy('date')
        ->get();

    $attendanceByStudent = $attendances->groupBy('student_id');

    /*
    |--------------------------------------------------------------------------
    | Monthly student summaries
    |--------------------------------------------------------------------------
    */

    $studentSummaries = [];

    foreach ($class->students as $student) {

        $records = $attendanceByStudent->get(
            $student->id,
            collect()
        );

        $present = $records
            ->where('status', 'Present')
            ->count();

        $absent = $records
            ->where('status', 'Absent')
            ->count();

        $late = $records
            ->where('status', 'Late')
            ->count();

        $total = $records->count();

        $attended = $present + $late;

        $percentage = $total > 0
            ? round(($attended / $total) * 100, 2)
            : 0;

        $studentSummaries[$student->id] = [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'percentage' => $percentage,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly navigation data
    |--------------------------------------------------------------------------
    */

    $monthlyAttendance = collect([
        $selectedMonth => $attendances
    ]);

    return view(
        'teacher.attendance.report',
        compact(
            'teacher',
            'class',
            'semester',
            'selectedMonth',
            'monthDate',
            'previousMonth',
            'nextMonth',
            'attendances',
            'attendanceByStudent',
            'monthlyAttendance',
            'studentSummaries'
        )
    );
}
}