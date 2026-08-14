@extends('layouts.admin')

@section('content')

<div class="container">
<a
    href="{{ route('admin.students.import.template') }}"
    class="btn btn-primary mb-3"
>
    Download Excel Template
</a>
    <h2 class="mb-4">Import Students from Excel</h2>

    <div class="card">
        <div class="card-body">

            <div class="alert alert-info">
                <strong>Excel format:</strong>

                <br>

                Your Excel file must contain these columns:

                <br><br>

                <code>
                    Student ID | Student Name | Grade | Section | Gender
                </code>

                <br><br>

                Example:

                <br>

                <code>
                    ST002 | Abebe Kebede | 9 | A | Male
                </code>

                <br>

                <code>
                    ST003 | Hana Ali | 9 | A | Female
                </code>

                <br><br>

                Default student password:

                <strong>Student@123</strong>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following:</strong>

                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.students.import.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Select Excel File
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required
                    >

                </div>

                <button type="submit" class="btn btn-success">
                    Import Students
                </button>

                <a
                    href="{{ route('admin.students.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection