@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Exam Result</h1>
    <p>Your Score: {{ $score }} / {{ $total }}</p>
</div>
@endsection
