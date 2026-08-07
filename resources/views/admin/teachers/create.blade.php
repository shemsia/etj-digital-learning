@extends('layouts.admin')

@section('content')

<h2>Add New Teacher</h2>

<form action="{{ route('admin.teachers.store') }}" method="POST">

    @csrf

    <div class="mb-3">
        <label>Teacher Name</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label>Employee ID</label>
        <input type="text" name="employee_id" class="form-control">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">
        Save Teacher
    </button>

</form>

@endsection