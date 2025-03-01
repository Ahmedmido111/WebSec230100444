@extends('layouts.app')

@section('title', 'Grades List')

@section('content')
    <div class="card m-4">
        <div class="card-header">Grades List</div>
        <div class="card-body">
            <a href="{{ route('grades.create') }}" class="btn btn-success mb-3">Add New Grade</a>
            @foreach($terms as $term)
                <h3>{{ $term->name }}</h3>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Credit Hours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($term->grades as $grade)
                            <tr>
                                <td>{{ $grade->course }}</td>
                                <td>{{ $grade->grade }}</td>
                                <td>{{ $grade->credit_hours }}</td>
                                <td>
                                    <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('grades.destroy', $grade) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>Total Credit Hours: {{ $term->total_credit_hours }}</p>
                <p>GPA: {{ $term->gpa }}</p>
            @endforeach
            <h3>Cumulative</h3>
            <p>Total Credit Hours: {{ $cumulative_total_credit_hours }}</p>
            <p>CGPA: {{ $cumulative_gpa }}</p>
        </div>
    </div>
@endsection
