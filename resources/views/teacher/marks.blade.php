@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Enter Student Marks</h2>

        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Select Class, Subject and Exam Type --}}
    <form method="GET" action="{{ route('teacher.marks') }}" class="mb-4">

        <div class="row g-3">

            {{-- Class --}}
            <div class="col-md-4">
                <label class="form-label">Class / Section</label>

                <select name="class_id" class="form-select" required>
                    <option value="">Select Class</option>

                    @foreach($classes as $class)
                        <option
                            value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}
                        >
                            Grade {{ $class->grade }} - {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Subject --}}
            <div class="col-md-4">
                <label class="form-label">Subject</label>

                <select name="subject_id" class="form-select" required>
                    <option value="">Select Subject</option>

                    @foreach($teacher->subjects as $subject)
                        <option
                            value="{{ $subject->id }}"
                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}
                        >
                            {{ $subject->name }} ({{ $subject->code }})
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Exam Type --}}
            <div class="col-md-3">
                <label class="form-label">Exam Type</label>

                <select name="exam_type" class="form-select" required>
                    <option value="">Select Exam Type</option>

                    <option value="Quiz"
                        {{ request('exam_type') == 'Quiz' ? 'selected' : '' }}>
                        Quiz
                    </option>

                    <option value="Midterm"
                        {{ request('exam_type') == 'Midterm' ? 'selected' : '' }}>
                        Midterm
                    </option>

                    <option value="Final"
                        {{ request('exam_type') == 'Final' ? 'selected' : '' }}>
                        Final
                    </option>

                    <option value="Assignment"
                        {{ request('exam_type') == 'Assignment' ? 'selected' : '' }}>
                        Assignment
                    </option>
                </select>
            </div>


            {{-- Load Button --}}
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    Load
                </button>
            </div>

        </div>

    </form>


    {{-- Students --}}
    @if($students->count() > 0)

        <form method="POST" action="{{ route('teacher.marks.store') }}">

            @csrf

            {{-- Hidden values --}}
            <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
            <input type="hidden" name="exam_type" value="{{ request('exam_type') }}">


            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Score</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($students as $index => $student)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $student->student_id }}
                                </td>

                                <td>
                                    {{ $student->user->name }}
                                </td>

                                <td style="max-width: 150px;">

                                    <input
                                        type="number"
                                        name="marks[{{ $student->id }}]"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        placeholder="0-100"
                                        value="{{ old('marks.' . $student->id, optional($existingMarks->get($student->id))->score) }}"
                                    >

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <button type="submit" class="btn btn-success">
                Save Marks
            </button>

        </form>


    @elseif(request()->filled('class_id'))

        <div class="alert alert-warning">
            No students found in this class.
        </div>

    @endif

</div>

@endsection