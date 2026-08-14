@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Students</h2>

        <a href="{{ route('admin.students.create') }}" class="btn btn-success">
            Add New Student
        </a>
    </div>
<form method="GET" action="{{ route('admin.students.index') }}" class="row g-3 mb-4">

    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Student name or ID"
            value="{{ request('search') }}"
        >
    </div>

    <div class="col-md-3">
        <label class="form-label">Grade</label>
        <select name="grade" class="form-select">
            <option value="">All Grades</option>

            @foreach($classes->pluck('grade')->unique()->sort() as $grade)
                <option
                    value="{{ $grade }}"
                    {{ request('grade') == $grade ? 'selected' : '' }}
                >
                    Grade {{ $grade }}
                </option>
            @endforeach
        </select>
    </div>

<div class="col-md-3">
    <label class="form-label">Section</label>

    <select name="section" id="section" class="form-select">
        <option value="">All Sections</option>

        @foreach($classes as $class)
            <option
                value="{{ $class->name }}"
                data-grade="{{ $class->grade }}"
                {{ request('section') == $class->name ? 'selected' : '' }}
            >
                {{ $class->name }}
            </option>
        @endforeach
    </select>
</div>

    <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary">
            Search
        </button>

        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            Reset
        </a>
    </div>

</form>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Grade</th>
                    <th>Section</th>
                    <th>Gender</th>
                    <th>Ations</th>
                </tr>
            </thead>

            <tbody>

                @forelse($students as $student)

                    <tr>
                        <td>{{ $student->id }}</td>

                        <td>{{ $student->student_id }}</td>

                        <td>{{ $student->user->name }}</td>

                        <td>{{ $student->user->email }}</td>

                        <td>Grade {{ $student->class->grade }}</td>

                        <td>{{ $student->class->name }}</td>

                        <td>{{ $student->gender }}</td>
                        <td>
    <a href="{{ route('admin.students.edit', $student->id) }}"
       class="btn btn-primary btn-sm">
        Edit
    </a>
<form action="{{ route('admin.students.reset-password', $student->id) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Reset this student password to Student@123?');">

    @csrf

    <button type="submit" class="btn btn-warning btn-sm">
        Reset Password
    </button>
</form>
    <form action="{{ route('admin.students.destroy', $student->id) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this student?');">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger btn-sm">
            Delete
        </button>
    </form>
</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center">
                            No students found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const gradeSelect = document.querySelector('select[name="grade"]');
    const sectionSelect = document.getElementById('section');

    function filterSections() {
        const selectedGrade = gradeSelect.value;

        Array.from(sectionSelect.options).forEach(function (option) {

            if (option.value === '') {
                option.hidden = false;
                return;
            }

            option.hidden =
                selectedGrade !== '' &&
                option.dataset.grade !== selectedGrade;
        });

        // If selected section doesn't belong to selected grade
        const selectedOption =
            sectionSelect.options[sectionSelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value !== '' &&
            selectedGrade !== '' &&
            selectedOption.dataset.grade !== selectedGrade
        ) {
            sectionSelect.value = '';
        }
    }

    gradeSelect.addEventListener('change', filterSections);

    filterSections();
});
</script>
@endsection