@extends('layouts.admin')

@section('content')

<div class="container">

    <h2 class="mb-4">Add Student</h2>

    <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf

        <!-- Student Name -->
        <div class="mb-3">
            <label class="form-label">Student Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
            >

            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Student ID -->
        <div class="mb-3">
            <label class="form-label">Student ID</label>

            <input
                type="text"
                name="student_id"
                class="form-control"
                value="{{ old('student_id') }}"
                placeholder="Example: ST001"
                required
            >

            @error('student_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>

            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                placeholder="student@example.com"
                required
            >

            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label">Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >

            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Grade & Section -->
        <div class="mb-3">
            <label class="form-label">Grade & Section</label>

            <select name="class_id" class="form-control" required>
                <option value="">Select Grade & Section</option>

                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                        {{ old('class_id') == $class->id ? 'selected' : '' }}>
                        Grade {{ $class->grade }} - Section {{ $class->name }}
                    </option>
                @endforeach

            </select>

            @error('class_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Gender -->
        <div class="mb-3">
            <label class="form-label">Gender</label>

            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                    Male
                </option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                    Female
                </option>
            </select>

            @error('gender')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            Create Student
        </button>

        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection