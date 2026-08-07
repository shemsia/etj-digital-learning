@extends('layouts.admin')

@section('content')

<h2>Classes</h2>

<a href="{{ route('admin.classes.create') }}" class="btn btn-success">
    Add New Class
</a>

<table class="table mt-3">

<tr>
    <th>ID</th>
    <th>Class Name</th>
</tr>

@foreach($classes as $class)

<tr>
    <td>{{ $class->id }}</td>
    <td>{{ $class->name }}</td>
</tr>

@endforeach

</table>

@endsection