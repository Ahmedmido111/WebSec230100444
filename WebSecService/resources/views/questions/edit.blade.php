@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Question</h1>
    <form action="{{ route('questions.update', $question->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="question_text">Question Text</label>
            <input type="text" name="question_text" class="form-control" value="{{ $question->question_text }}" required>
        </div>
        <div class="form-group">
            <label for="option_a">Option A</label>
            <input type="text" name="option_a" class="form-control" value="{{ $question->option_a }}" required>
        </div>
        <div class="form-group">
            <label for="option_b">Option B</label>
            <input type="text" name="option_b" class="form-control" value="{{ $question->option_b }}" required>
        </div>
        <div class="form-group">
            <label for="option_c">Option C</label>
            <input type="text" name="option_c" class="form-control" value="{{ $question->option_c }}" required>
        </div>
        <div class="form-group">
            <label for="option_d">Option D</label>
            <input type="text" name="option_d" class="form-control" value="{{ $question->option_d }}" required>
        </div>
        <div class="form-group">
            <label for="correct_option">Correct Option</label>
            <input type="text" name="correct_option" class="form-control" value="{{ $question->correct_option }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Question</button>
    </form>
</div>
@endsection
