@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{ route('send_reset_password') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          @foreach($errors->all() as $error)
            <div class="alert alert-danger">
              <strong>Error!</strong> {{$error}}
            </div>
          @endforeach
          @if(session('success'))
            <div class="alert alert-success">
              <strong>Success!</strong> {{ session('success') }}
            </div>
          @endif
        </div>
        <div class="form-group mb-2">
          <label for="email" class="form-label">Email:</label>
          <input type="email" class="form-control" placeholder="Enter your email" name="email" required>
        </div>
        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 