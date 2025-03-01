@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Start Exam</h1>
    <form action="{{ route('exam.submit') }}" method="POST">
        @csrf
        @foreach($questions as $question)
        <div class="form-group">
            <label>{{ $question->question_text }}</label>
            <div>
                <input type="radio" name="answers[{{ $question->id }}]" value="a" required> {{ $question->option_a }}
            </div>
            <div>
                <input type="radio" name="answers[{{ $question->id }}]" value="b" required> {{ $question->option_b }}
            </div>
            <div>
                <input type="radio" name="answers[{{ $question->id }}]" value="c" required> {{ $question->option_c }}
            </div>
            <div>
                <input type="radio" name="answers[{{ $question->id }}]" value="d" required> {{ $question->option_d }}
            </div>
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Submit Exam</button>
    </form>
</div>
@endsection
