@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Classes & Sections</h2>

<a href="{{ route('admin.classes.create') }}" class="btn btn-success">
    Add New Section
</a>

<table class="table table-bordered table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Grade</th>
            <th>Section</th>
        </tr>
    </thead>

    <tbody>
        @foreach($classes as $class)
            <tr>
                <td>{{ $class->id }}</td>
                <td>Grade {{ $class->grade }}</td>
                <td>{{ $class->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection