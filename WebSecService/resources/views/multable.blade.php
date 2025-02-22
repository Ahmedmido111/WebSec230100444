@extends('layouts.app')

@section('title', 'Multiplication Table of 5')

@section('content')
    <div class="card m-4 col-md-6">
        <div class="card-header">Multiplication Table of 5</div>
        <div class="card-body">
            @for ($i = 1; $i <= 10; $i++)
                <p>5 * {{ $i }} = {{ 5 * $i }}</p>
            @endfor
        </div>
    </div>
@endsection
