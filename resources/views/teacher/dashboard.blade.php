@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Teacher Dashboard
</h1>

<p class="mb-6">
    Welcome, {{ auth()->user()->name }}!
</p>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold">Teacher Panel</h2>
    <p>You can manage your courses, lessons, and students from here.</p>
</div>

@endsection