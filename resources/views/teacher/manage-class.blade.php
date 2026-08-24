@extends('layouts.admin')

@section('content')

<div class="py-6">

    {{-- Header --}}
    <div class="mb-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Grade {{ $class->grade }} - {{ $class->name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    Manage class students, attendance, assessments and assignments
                </p>
            </div>

            <a
                href="{{ route('teacher.dashboard') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
            >
                Back to Dashboard
            </a>

        </div>

    </div>


    {{-- Active Semester --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <h2 class="text-lg font-bold text-gray-800">
            Current Academic Semester
        </h2>

        @if($activeSemester)

            <div class="mt-3 flex items-center">

                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-md font-semibold">
                    {{ $activeSemester->name }} is Active
                </span>

            </div>

        @else

            <div class="mt-3 p-4 bg-yellow-100 text-yellow-800 rounded-md">
                No semester is currently active.
                Please contact the administrator.
            </div>

        @endif

    </div>


    {{-- Students Summary --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Class Students
        </h2>

        <p class="text-gray-600 mt-2">
            Total Students:
            <span class="font-semibold">
                {{ $class->students->count() }}
            </span>
        </p>

    </div>


    {{-- Semester I --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Semester I
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Attendance, continuous assessment and assignments
                </p>
            </div>

            @if($activeSemester && $activeSemester->name === 'Semester I')

                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                    Active
                </span>

            @else

                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm">
                    Inactive
                </span>

            @endif

        </div>


        {{-- Attendance --}}
        <div class="border rounded-lg p-4 mb-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Attendance
                    </h3>

                    <p class="text-sm text-gray-500">
                        Record and manage student attendance
                    </p>
                </div>

                @if($activeSemester && $activeSemester->name === 'Semester I')

                    <a
                        href="{{ route('teacher.attendance.index', $class->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        View / Edit
                    </a>

                @else

                    <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                        Not Active
                    </span>

                @endif

            </div>

        </div>


        {{-- Continuous Assessment --}}
        <div class="border rounded-lg p-4 mb-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Continuous Assessment
                    </h3>

                    <p class="text-sm text-gray-500">
                        Manage continuous assessment marks
                    </p>
                </div>

                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                    Coming Soon
                </span>

            </div>

        </div>


        {{-- Assignment --}}
        <div class="border rounded-lg p-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Assignment
                    </h3>

                    <p class="text-sm text-gray-500">
                        Send and manage assignments
                    </p>
                </div>

                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                    Coming Soon
                </span>

            </div>

        </div>

    </div>


    {{-- Semester II --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Semester II
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Attendance, continuous assessment and assignments
                </p>
            </div>

            @if($activeSemester && $activeSemester->name === 'Semester II')

                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                    Active
                </span>

            @else

                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm">
                    Inactive
                </span>

            @endif

        </div>


        {{-- Attendance --}}
        <div class="border rounded-lg p-4 mb-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Attendance
                    </h3>

                    <p class="text-sm text-gray-500">
                        Record and manage student attendance
                    </p>
                </div>

                @if($activeSemester && $activeSemester->name === 'Semester II')

                    <a
                        href="{{ route('teacher.attendance.index', $class->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        View / Edit
                    </a>

                @else

                    <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                        Not Active
                    </span>

                @endif

            </div>

        </div>


        {{-- Continuous Assessment --}}
        <div class="border rounded-lg p-4 mb-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Continuous Assessment
                    </h3>

                    <p class="text-sm text-gray-500">
                        Manage continuous assessment marks
                    </p>
                </div>

                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                    Coming Soon
                </span>

            </div>

        </div>


        {{-- Assignment --}}
        <div class="border rounded-lg p-4">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Assignment
                    </h3>

                    <p class="text-sm text-gray-500">
                        Send and manage assignments
                    </p>
                </div>

                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md">
                    Coming Soon
                </span>

            </div>

        </div>

    </div>

</div>

@endsection