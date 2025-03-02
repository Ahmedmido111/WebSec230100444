@extends('layouts.app')

@section('title', 'Add New Grade')

@section('content')
<div class="container">
    <h1>Add New Grade</h1>
    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="course">Course</label>
            <input type="text" name="course" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <input type="text" name="grade" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="credit_hours">Credit Hours</label>
            <input type="number" name="credit_hours" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Grade</button>
    </form>
</div>
@endsection
