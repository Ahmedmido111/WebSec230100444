@extends('layouts.app')

@section('title', 'Users List')

@section('content')
    <div class="card m-4">
        <div class="card-header">Users List</div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="form-group">
                    <label for="filter">Filter by name or email:</label>
                    <input type="text" name="filter" class="form-control" value="{{ request('filter') }}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <a href="{{ route('users.create') }}" class="btn btn-success mt-3">Add New User</a>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
