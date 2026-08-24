@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Admin Dashboard
</h1>

<p class="mb-6">
    Welcome, {{ auth()->user()->name }}!
</p>

<div class="grid grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Teachers</h2>
        <p class="text-3xl">0</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Students</h2>
        <p class="text-3xl">1</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Courses</h2>
        <p class="text-3xl">0</p>
    </div>
<div class="bg-white shadow-sm rounded-lg p-6 mt-6">

    <h2 class="text-xl font-bold text-gray-800 mb-2">
        Academic Semester
    </h2>

    <p class="text-sm text-gray-500 mb-6">
        Select the semester that is currently active.
    </p>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">

        @foreach($semesters as $semester)

            <div class="flex items-center justify-between border rounded-lg p-4">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        {{ $semester->name }}
                    </h3>

                    @if($semester->is_active)
                        <span class="text-sm text-green-600 font-semibold">
                            ● Active
                        </span>
                    @else
                        <span class="text-sm text-gray-500">
                            ● Inactive
                        </span>
                    @endif
                </div>

                @if($semester->is_active)

                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-md font-semibold">
                        Active
                    </span>

                @else

                    <form
                        action="{{ route('admin.semester.activate', $semester->id) }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Activate
                        </button>
                    </form>

                @endif

            </div>

        @endforeach

    </div>

</div>
</div>

@endsection