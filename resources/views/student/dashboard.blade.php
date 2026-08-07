@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Student Dashboard
</h1>

<p class="mb-6">
    Welcome, {{ auth()->user()->name }}!
</p>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold">Student Panel</h2>
    <p>You can view courses, lessons, exams, and certificates here.</p>
</div>

@endsection