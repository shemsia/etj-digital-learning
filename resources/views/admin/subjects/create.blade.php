@extends('layouts.admin')

@section('content')

<h2>Add New Subject</h2>

<form action="{{ route('admin.subjects.store') }}" method="POST">

    @csrf

    <div>
        <label>Subject Name</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mt-3">
        <label>Subject Code</label>
        <input type="text" name="code" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary mt-3">
        Save Subject
    </button>

</form>

@endsection