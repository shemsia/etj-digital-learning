@extends('layouts.admin')

@section('content')

<div class="py-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Grade {{ $class->grade }} - {{ $class->name }}
        </h1>

        <p class="text-gray-600 mt-1">
            Class Management
        </p>
    </div>


    {{-- Students Summary --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <h2 class="text-lg font-bold text-gray-800 mb-2">
            Students
        </h2>

        <p class="text-gray-600">
            Total Students:
            <span class="font-semibold">
                {{ $class->students->count() }}
            </span>
        </p>

    </div>


    {{-- Semester I --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <h2 class="text-xl font-bold text-gray-800 mb-5">
            Semester I
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Attendance --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Attendance
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Record and manage student attendance.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                        Edit
                    </a>

                </div>

            </div>


            {{-- Continuous Assessment --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Continuous Assessment
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    View and manage continuous assessment marks.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                        Edit
                    </a>

                </div>

            </div>


            {{-- Assignment --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Assignment
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Send and view assignments for students.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700">
                        Send
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Semester II --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

        <h2 class="text-xl font-bold text-gray-800 mb-5">
            Semester II
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Attendance --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Attendance
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Record and manage student attendance.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                        Edit
                    </a>

                </div>

            </div>


            {{-- Continuous Assessment --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Continuous Assessment
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    View and manage continuous assessment marks.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                        Edit
                    </a>

                </div>

            </div>


            {{-- Assignment --}}
            <div class="border rounded-lg p-5">

                <h3 class="font-semibold text-lg">
                    Assignment
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Send and view assignments for students.
                </p>

                <div class="flex gap-2">

                    <a href="#"
                       class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        View
                    </a>

                    <a href="#"
                       class="px-3 py-2 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700">
                        Send
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Back --}}
    <div>
        <a href="{{ route('teacher.dashboard') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            ← Back to Dashboard
        </a>
    </div>

</div>

@endsection