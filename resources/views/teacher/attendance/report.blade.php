<x-app-layout>

    <x-slot name="header">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Attendance Report
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                {{ $class->name }} —
                {{ $semester->name }}
            </p>
        </div>

        <a
            href="{{ route('teacher.attendance.index', $class->id) }}"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-center"
        >
            ← Back to Attendance
        </a>
<a
    href="{{ route('teacher.attendance.report.pdf', ['classId' => $class->id, 'month' => $selectedMonth]) }}"
    class="inline-flex items-center px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition"
>
    📄 Download PDF
</a>
    </div>
</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{-- Month Navigation --}}
<div class="bg-white rounded-lg shadow p-4 mb-6">

    <div class="flex flex-col md:flex-row items-center justify-between gap-4">

        {{-- Previous Month --}}
        <a
            href="{{ route('teacher.attendance.report', [
                'classId' => $class->id,
                'month' => $previousMonth
            ]) }}"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"
        >
            ← Previous Month
        </a>

        {{-- Current Month --}}
        <div class="text-center">

            <p class="text-sm text-gray-500">
                Attendance Month
            </p>

            <h3 class="text-xl font-bold text-gray-800">
                {{ $monthDate->format('F Y') }}
            </h3>

        </div>

        {{-- Next Month --}}
        <a
            href="{{ route('teacher.attendance.report', [
                'classId' => $class->id,
                'month' => $nextMonth
            ]) }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            Next Month →
        </a>

    </div>

</div>
           {{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">
            Total Students
        </p>

        <p class="text-3xl font-bold text-gray-800">
            {{ $class->students->count() }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">
            Attendance Records
        </p>

        <p class="text-3xl font-bold text-blue-600">
            {{ $attendances->count() }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">
            Present
        </p>

        <p class="text-3xl font-bold text-green-600">
            {{ $attendances->where('status', 'Present')->count() }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">
            Absent
        </p>

        <p class="text-3xl font-bold text-red-600">
            {{ $attendances->where('status', 'Absent')->count() }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">
            Late
        </p>

        <p class="text-3xl font-bold text-yellow-600">
            {{ $attendances->where('status', 'Late')->count() }}
        </p>
    </div>

</div>


            {{-- Student Summary --}}
            <div class="bg-white rounded-lg shadow mb-6">

                <div class="p-5 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Student Attendance Summary
                    </h3>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    #
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Student
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Total Days
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Present
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Absent
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Late
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Attendance %
                                </th>
                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($class->students as $index => $student)

                                @php
                                    $summary = $studentSummaries[$student->id];
                                @endphp

                                <tr>

                                    <td class="px-6 py-4">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $student->user->name ?? 'N/A' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        {{ $summary['total'] }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-green-600 font-semibold">
                                        {{ $summary['present'] }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-red-600 font-semibold">
                                        {{ $summary['absent'] }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-yellow-600 font-semibold">
                                        {{ $summary['late'] }}
                                    </td>

                                    <td class="px-6 py-4 text-center font-semibold">
                                        {{ $summary['percentage'] }}%
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


           {{-- Monthly Attendance --}}
@forelse($monthlyAttendance as $month => $records)

    @php
        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month);
        $daysInMonth = $monthDate->daysInMonth;

        // Create quick lookup:
        // student_id + date => attendance status
        $attendanceMap = $records->keyBy(function ($attendance) {
            return $attendance->student_id . '_' .
                \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');
        });
    @endphp

    <div class="bg-white rounded-lg shadow mb-6">

        {{-- Month Header --}}
        <div class="p-5 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ $monthDate->format('F Y') }}
            </h3>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-max divide-y divide-gray-200">

                {{-- Table Header --}}
                <thead class="bg-gray-50">

                    <tr>

                        {{-- Student --}}
                        <th class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r">
                            Student
                        </th>

                        {{-- Days --}}
                        @for($day = 1; $day <= $daysInMonth; $day++)

                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                {{ $monthDate->copy()->day($day)->format('M j') }}
                            </th>

                        @endfor

                        {{-- Totals --}}
                        <th class="px-3 py-3 text-center text-xs font-medium text-green-600 uppercase border-l">
                            P
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium text-red-600 uppercase">
                            A
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium text-yellow-600 uppercase">
                            L
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-600 uppercase">
                            %
                        </th>

                    </tr>

                </thead>

                {{-- Students --}}
                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($class->students as $student)

                        @php
                            $summary = $studentSummaries[$student->id];
                        @endphp

                        <tr>

                            {{-- Student Name --}}
                            <td class="sticky left-0 z-10 bg-white px-4 py-3 font-medium text-gray-900 border-r whitespace-nowrap">
                                {{ $student->user->name ?? 'N/A' }}
                            </td>

                            {{-- Daily Attendance --}}
                            @for($day = 1; $day <= $daysInMonth; $day++)

                                @php
                                    $currentDate = $monthDate
                                        ->copy()
                                        ->day($day)
                                        ->format('Y-m-d');

                                    $key = $student->id . '_' . $currentDate;

                                    $attendance = $attendanceMap->get($key);
                                @endphp

                                <td class="px-3 py-3 text-center">

                                    @if($attendance)

                                        @if($attendance->status === 'Present')

                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700 font-bold text-xs"
                                                title="Present"
                                            >
                                                P
                                            </span>

                                        @elseif($attendance->status === 'Absent')

                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-100 text-red-700 font-bold text-xs"
                                                title="Absent"
                                            >
                                                A
                                            </span>

                                        @elseif($attendance->status === 'Late')

                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-yellow-100 text-yellow-700 font-bold text-xs"
                                                title="Late"
                                            >
                                                L
                                            </span>

                                        @endif

                                    @else

                                        <span class="text-gray-300">
                                            —
                                        </span>

                                    @endif

                                </td>

                            @endfor

                            {{-- Present --}}
                            <td class="px-3 py-3 text-center text-green-600 font-semibold border-l">
                                {{ $summary['present'] }}
                            </td>

                            {{-- Absent --}}
                            <td class="px-3 py-3 text-center text-red-600 font-semibold">
                                {{ $summary['absent'] }}
                            </td>

                            {{-- Late --}}
                            <td class="px-3 py-3 text-center text-yellow-600 font-semibold">
                                {{ $summary['late'] }}
                            </td>

                            {{-- Percentage --}}
                            <td class="px-3 py-3 text-center font-semibold">
                                {{ $summary['percentage'] }}%
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- Legend --}}
        <div class="px-5 py-3 border-t bg-gray-50 text-sm text-gray-600">

            <div class="flex flex-wrap items-center gap-5">

                <span class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">
                        P
                    </span>
                    Present
                </span>

                <span class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-700 font-bold text-xs">
                        A
                    </span>
                    Absent
                </span>

                <span class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 font-bold text-xs">
                        L
                    </span>
                    Late
                </span>

                <span>
                    — No record
                </span>

            </div>

        </div>

    </div>

@empty

    <div class="bg-white rounded-lg shadow p-8 text-center">

        <p class="text-gray-500">
            No attendance records have been recorded yet.
        </p>

    </div>

@endforelse

        </div>
    </div>

</x-app-layout>