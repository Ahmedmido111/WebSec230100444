@extends('layouts.app')

@section('title', 'Even Numbers')

@section('content')
    <div class="card m-4 ">
        <div class="card-header">Even Numbers</div>
        <div class="card-body">
            <table>
                @foreach (range(1, 100) as $i)
                    @if ($i % 2 == 0)
                        <span class="badge badge-primary m-1">{{ $i }}&nbsp</span>
                    @else
                        <span class="badge badge-secondary m-1">{{ $i }}&nbsp</span>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
@endsection
