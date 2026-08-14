@extends('layouts.admin')

@section('content')

<div class="container">

    <h2 class="mb-4">Edit Student</h2>

    <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
        @csrf
        @method('PUT')

        <!-- Student Name -->
        <div class="mb-3">
            <label class="form-label">Student Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $student->user->name) }}"
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
                value="{{ old('student_id', $student->student_id) }}"
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
                value="{{ old('email', $student->user->email) }}"
                required
            >

            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Grade & Section -->
        <div class="mb-3">
            <label class="form-label">Grade & Section</label>

            <select name="class_id" class="form-control" required>

                @foreach($classes as $class)

                    <option
                        value="{{ $class->id }}"
                        {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}
                    >
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

                <option value="Male"
                    {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>
                    Male
                </option>

                <option value="Female"
                    {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>
                    Female
                </option>

            </select>

            @error('gender')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Update Student
        </button>

        <a href="{{ route('admin.students.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection