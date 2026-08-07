@extends('layouts.admin')

@section('content')

<h2>Subjects</h2>

<a href="{{ route('admin.subjects.create') }}" class="btn btn-success">
    Add New Subject
</a>

<table class="table mt-3">

<tr>
    <th>ID</th>
    <th>Subject Name</th>
    <th>Code</th>
</tr>

@foreach($subjects as $subject)

<tr>
    <td>{{ $subject->id }}</td>
    <td>{{ $subject->name }}</td>
    <td>{{ $subject->code }}</td>
</tr>

@endforeach

</table>

@endsection