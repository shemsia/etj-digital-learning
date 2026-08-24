<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Attendance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        h3 {
            margin-top: 15px;
            margin-bottom: 8px;
        }

        .info {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        .student {
            text-align: left;
            white-space: nowrap;
        }

        .present {
            font-weight: bold;
        }

        .absent {
            font-weight: bold;
        }

        .late {
            font-weight: bold;
        }

        .summary-box {
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-box td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: center;
        }

        .summary-box strong {
            display: block;
            font-size: 14px;
        }

        .legend {
            margin-top: 10px;
            font-size: 9px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
        }

        .page-break {
            page-break-after: always;
        }

        .no-data {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>

<body>

{{-- ========================================================= --}}
{{-- MONTHLY ATTENDANCE PAGES                                 --}}
{{-- ========================================================= --}}

@foreach($monthlyReports as $monthIndex => $monthlyReport)

    @php
       $monthDate = $monthlyReport['date'];

$ethiopianMonthDate = new \Andegna\DateTime(
    new \DateTime(
        $monthDate->format('Y-m-d')
    )
);

$attendances = $monthlyReport['attendances'];

        $attendanceByStudent =
            $monthlyReport['attendanceByStudent'];

        $monthlySummaries =
            $monthlyReport['studentSummaries'];

        /*
        |--------------------------------------------------------------------------
        | Monthly totals
        |--------------------------------------------------------------------------
        */

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLate = 0;
        $totalRecords = 0;

        foreach ($monthlySummaries as $summary) {
            $totalPresent += $summary['present'];
            $totalAbsent += $summary['absent'];
            $totalLate += $summary['late'];
            $totalRecords += $summary['total'];
        }

        $totalAttended =
            $totalPresent + $totalLate;

        $overallPercentage =
            $totalRecords > 0
                ? round(
                    ($totalAttended / $totalRecords) * 100,
                    2
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Days in this month
        |--------------------------------------------------------------------------
        */

        $daysInMonth = $monthDate->daysInMonth;

        /*
        | Don't display future days in the current month.
        */

        if (
            $monthDate->format('Y-m') === now()->format('Y-m')
        ) {
            $daysInMonth = now()->day;
        }
    @endphp


    {{-- ===================================================== --}}
    {{-- HEADER                                                --}}
    {{-- ===================================================== --}}

    <h1>Attendance Report</h1>

    <div class="info">

        <strong>
            Grade {{ $class->grade }} - {{ $class->name }}
        </strong>

        <br>

        Semester:
        {{ $semester->name ?? 'Active Semester' }}

        <br>

        <strong>
            {{ $monthDate->format('F Y') }}
        </strong>

    </div>


    {{-- ===================================================== --}}
    {{-- MONTHLY SUMMARY BOX                                   --}}
    {{-- ===================================================== --}}

    <table class="summary-box">

        <tr>

            <td>
                <strong>
                    {{ $class->students->count() }}
                </strong>
                Students
            </td>

            <td>
                <strong>
                    {{ $totalRecords }}
                </strong>
                Records
            </td>

            <td>
                <strong>
                    {{ $totalPresent }}
                </strong>
                Present
            </td>

            <td>
                <strong>
                    {{ $totalAbsent }}
                </strong>
                Absent
            </td>

            <td>
                <strong>
                    {{ $totalLate }}
                </strong>
                Late
            </td>

            <td>
                <strong>
                    {{ $overallPercentage }}%
                </strong>
                Attendance
            </td>

        </tr>

    </table>


    {{-- ===================================================== --}}
    {{-- MONTHLY REGISTER                                     --}}
    {{-- ===================================================== --}}

  <h3>
   {{ $monthDate->format('F Y') }} Attendance Register
</h3>


    @if($attendances->count() > 0)

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th class="student">
                        Student
                    </th>

                    @for(
                        $day = 1;
                        $day <= $daysInMonth;
                        $day++
                    )

                        <th>
                            {{ $day }}
                        </th>

                    @endfor

                    <th>P</th>
                    <th>A</th>
                    <th>L</th>
                    <th>%</th>

                </tr>

            </thead>


            <tbody>

                @foreach(
                    $class->students
                    as $index => $student
                )

                    @php

                        $records =
                            $attendanceByStudent->get(
                                $student->id,
                                collect()
                            );

                        $attendanceMap =
                            $records->keyBy(
                                fn ($attendance) =>
                                    \Carbon\Carbon::parse(
                                        $attendance->date
                                    )->day
                            );

                        $summary =
                            $monthlySummaries[
                                $student->id
                            ];

                    @endphp


                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>


                        <td class="student">
                            {{ $student->user->name ?? 'Student' }}
                        </td>


                        @for(
                            $day = 1;
                            $day <= $daysInMonth;
                            $day++
                        )

                            @php
                                $attendance =
                                    $attendanceMap->get($day);
                            @endphp


                            <td>

                                @if($attendance)

                                    @if(
                                        $attendance->status
                                        === 'Present'
                                    )

                                        P

                                    @elseif(
                                        $attendance->status
                                        === 'Absent'
                                    )

                                        A

                                    @elseif(
                                        $attendance->status
                                        === 'Late'
                                    )

                                        L

                                    @endif

                                @else

                                    —

                                @endif

                            </td>

                        @endfor


                        <td class="present">
                            {{ $summary['present'] }}
                        </td>

                        <td class="absent">
                            {{ $summary['absent'] }}
                        </td>

                        <td class="late">
                            {{ $summary['late'] }}
                        </td>

                        <td>
                            {{ $summary['percentage'] }}%
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="no-data">
            No attendance records were recorded
            for {{ $monthDate->format('F Y') }}.
        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- LEGEND                                                --}}
    {{-- ===================================================== --}}

    <div class="legend">

        <strong>P</strong> = Present
        &nbsp;&nbsp;

        <strong>A</strong> = Absent
        &nbsp;&nbsp;

        <strong>L</strong> = Late
        &nbsp;&nbsp;

        <strong>—</strong> = No attendance recorded

    </div>


    <div class="footer">

        Generated by ETJ Digital Learning

    </div>


    {{-- ===================================================== --}}
    {{-- PAGE BREAK                                            --}}
    {{-- ===================================================== --}}

    @if(!$loop->last)

        <div class="page-break"></div>

    @endif

@endforeach



{{-- ========================================================= --}}
{{-- FINAL CUMULATIVE STUDENT SUMMARY PAGE                    --}}
{{-- ========================================================= --}}

<div class="page-break"></div>


<h1>
    Student Attendance Summary
</h1>


<div class="info">

    <strong>
        Grade {{ $class->grade }} - {{ $class->name }}
    </strong>

    <br>

    Semester:
    {{ $semester->name ?? 'Active Semester' }}

    <br>



Period:
{{ $firstMonth->format('F Y') }}
-
{{ $lastAllowedMonth->format('F Y') }}

</div>


{{-- ========================================================= --}}
{{-- OVERALL TOTALS                                           --}}
{{-- ========================================================= --}}

@php

    $totalPresent = 0;
    $totalAbsent = 0;
    $totalLate = 0;
    $totalRecords = 0;

    foreach ($studentSummaries as $summary) {

        $totalPresent += $summary['present'];

        $totalAbsent += $summary['absent'];

        $totalLate += $summary['late'];

        $totalRecords += $summary['total'];

    }

    $totalAttended =
        $totalPresent + $totalLate;

    $overallPercentage =
        $totalRecords > 0
            ? round(
                ($totalAttended / $totalRecords) * 100,
                2
            )
            : 0;

@endphp


<table class="summary-box">

    <tr>

        <td>
            <strong>
                {{ $class->students->count() }}
            </strong>
            Students
        </td>

        <td>
            <strong>
                {{ $totalRecords }}
            </strong>
            Total Records
        </td>

        <td>
            <strong>
                {{ $totalPresent }}
            </strong>
            Present
        </td>

        <td>
            <strong>
                {{ $totalAbsent }}
            </strong>
            Absent
        </td>

        <td>
            <strong>
                {{ $totalLate }}
            </strong>
            Late
        </td>

        <td>
            <strong>
                {{ $overallPercentage }}%
            </strong>
            Attendance
        </td>

    </tr>

</table>


{{-- ========================================================= --}}
{{-- STUDENT SUMMARY TABLE                                   --}}
{{-- ========================================================= --}}

<h3>
    Overall Student Attendance
</h3>


<table>

    <thead>

        <tr>

            <th>#</th>

            <th class="student">
                Student
            </th>

            <th>
                Total Days
            </th>

            <th>
                Present
            </th>

            <th>
                Absent
            </th>

            <th>
                Late
            </th>

            <th>
                Attendance %
            </th>

        </tr>

    </thead>


    <tbody>

        @foreach(
            $class->students
            as $index => $student
        )

            @php

                $summary =
                    $studentSummaries[
                        $student->id
                    ];

            @endphp


            <tr>

                <td>
                    {{ $index + 1 }}
                </td>


                <td class="student">

                    {{ $student->user->name ?? 'Student' }}

                </td>


                <td>
                    {{ $summary['total'] }}
                </td>


                <td class="present">

                    {{ $summary['present'] }}

                </td>


                <td class="absent">

                    {{ $summary['absent'] }}

                </td>


                <td class="late">

                    {{ $summary['late'] }}

                </td>


                <td>

                    {{ $summary['percentage'] }}%

                </td>

            </tr>

        @endforeach

    </tbody>

</table>


<div class="legend">

    <strong>P</strong> = Present
    &nbsp;&nbsp;

    <strong>A</strong> = Absent
    &nbsp;&nbsp;

    <strong>L</strong> = Late

</div>


<div class="footer">

    Generated by ETJ Digital Learning

</div>

</body>
</html>