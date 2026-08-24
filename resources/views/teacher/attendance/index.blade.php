@extends('layouts.admin')

@section('content')

<div class="py-6">

    {{-- Header --}}
    <div class="mb-6">

        <a href="{{ route('teacher.class.manage', $class->id) }}"
           class="text-blue-600 hover:underline">
            ← Back to Class
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-3">
            Attendance
        </h1>

        <p class="text-gray-600">
            Grade {{ $class->grade }} - {{ $class->name }}
        </p>

    </div>


{{-- Date Selection --}}
<div class="bg-white shadow-sm rounded-lg p-6 mb-6">

    <form method="GET"
          action="{{ route('teacher.attendance.index', $class->id) }}">

        <div class="flex items-end gap-4">

            <div>
                <label for="date"
                       class="block text-sm font-medium text-gray-700 mb-1">
                    Attendance Date
                </label>

                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ $date }}"
                    class="border-gray-300 rounded-md shadow-sm"
                >
            </div>

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
                Load
            </button>

        </div>

    </form>

</div>





    {{-- Students --}}
<div class="bg-white shadow-sm rounded-lg p-6">

   <div class="flex items-center justify-between mb-4">

    <div>
        <h2 class="text-xl font-bold text-gray-800">
            Students
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
        </p>
    </div>

    <div>
        <a
            href="{{ route('teacher.attendance.report', $class->id) }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
        >
            View Attendance Report
        </a>
    </div>

</div>

    <form method="POST"
          action="{{ route('teacher.attendance.store', $class->id) }}">

        @csrf

        <input type="hidden" name="date" value="{{ $date }}">
    

        <div class="overflow-x-auto">

            <table class="min-w-full border border-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 border text-left">
                            #
                        </th>

                        <th class="px-4 py-3 border text-left">
                            Student ID
                        </th>

                        <th class="px-4 py-3 border text-left">
                            Student Name
                        </th>

                        <th class="px-4 py-3 border text-left">
                            Attendance
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($class->students as $student)

                        @php
                            $currentStatus = optional(
                                $attendances->get($student->id)
                            )->status;
                        @endphp

                        <tr>

                            <td class="px-4 py-3 border">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3 border">
                                {{ $student->student_id }}
                            </td>

                            <td class="px-4 py-3 border">
                                {{ $student->user->name }}
                            </td>

                            <td class="px-4 py-3 border">

                                <select
                                    name="attendance[{{ $student->id }}]"
                                    class="border-gray-300 rounded-md shadow-sm"
                                >

                                    <option value="Present"
                                        {{ $currentStatus === 'Present' ? 'selected' : '' }}>
                                        Present
                                    </option>

                                    <option value="Absent"
                                        {{ $currentStatus === 'Absent' ? 'selected' : '' }}>
                                        Absent
                                    </option>

                                    <option value="Late"
                                        {{ $currentStatus === 'Late' ? 'selected' : '' }}>
                                        Late
                                    </option>

                                </select>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="px-4 py-6 text-center text-gray-500">

                                No students found in this class.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($class->students->count() > 0)

            <div class="mt-6">

                <button
                    type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                >
                    Save Attendance
                </button>

            </div>

        @endif

    </form>

</div>

</div>

@endsection