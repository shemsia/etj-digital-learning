@extends('layouts.admin')

@section('content')

<div class="py-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Teacher Dashboard
        </h1>

        <p class="text-gray-600 mt-1">
            Welcome, {{ $teacher->user->name }}!
        </p>
    </div>


    {{-- My Classes --}}
    <div class="bg-white shadow-sm rounded-lg p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    My Classes
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Classes and sections assigned to you
                </p>
            </div>
        </div>


        @forelse($teacher->classes as $class)

            <div class="border rounded-lg p-5 mb-4">

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Grade {{ $class->grade }} - {{ $class->name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Manage students, attendance, assessments and assignments
                        </p>
                    </div>

                  <a
    href="{{ route('teacher.class.manage', $class->id) }}"
    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
>
    Manage Class
</a>

                </div>

            </div>

        @empty

            <div class="text-center py-8">

                <p class="text-gray-500">
                    No classes have been assigned to you yet.
                </p>

                <p class="text-sm text-gray-400 mt-2">
                    Please contact the administrator to assign your classes.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Subjects --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mt-6">

        <h2 class="text-xl font-bold text-gray-800 mb-4">
            My Subjects
        </h2>

        @forelse($teacher->subjects as $subject)

            <div class="border rounded-lg p-4 mb-3">

                <h3 class="font-semibold text-gray-800">
                    {{ $subject->name }}
                </h3>

                <p class="text-sm text-gray-500">
                    Subject Code: {{ $subject->code }}
                </p>

            </div>

        @empty

            <p class="text-gray-500">
                No subjects have been assigned to you yet.
            </p>

        @endforelse

    </div>

</div>

@endsections