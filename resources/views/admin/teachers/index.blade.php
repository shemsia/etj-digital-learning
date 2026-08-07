@extends('layouts.admin')

@section('content')

<h2>Teachers</h2>

<a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
    Add New Teacher
</a>

<table class="table mt-3">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Employee ID</th>
    <th>Phone</th>
</tr>

@foreach($teachers as $teacher)

<tr>
    <td>{{ $teacher->id }}</td>
    <td>{{ $teacher->user->name }}</td>
    <td>{{ $teacher->user->email }}</td>
    <td>{{ $teacher->employee_id }}</td>
    <td>{{ $teacher->phone }}</td>
</tr>

@endforeach

</table>

@endsection