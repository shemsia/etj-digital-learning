@extends('layouts.admin')

@section('content')

<h2>Add New Class</h2>

<form action="{{ route('admin.classes.store') }}" method="POST">

    @csrf

    <label>Class Name</label>

    <input type="text" name="name" class="form-control">

    <button type="submit" class="btn btn-primary mt-3">
        Save Class
    </button>

</form>

@endsection