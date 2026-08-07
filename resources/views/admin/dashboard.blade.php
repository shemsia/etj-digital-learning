@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Admin Dashboard
</h1>

<p class="mb-6">
    Welcome, {{ auth()->user()->name }}!
</p>

<div class="grid grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Teachers</h2>
        <p class="text-3xl">0</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Students</h2>
        <p class="text-3xl">1</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold">Courses</h2>
        <p class="text-3xl">0</p>
    </div>

</div>

@endsection