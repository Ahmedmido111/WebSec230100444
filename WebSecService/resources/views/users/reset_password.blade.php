@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{ route('update_password') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="form-group">
          @foreach($errors->all() as $error)
            <div class="alert alert-danger">
              <strong>Error!</strong> {{$error}}
            </div>
          @endforeach
        </div>

        <div class="form-group mb-2">
          <label for="password" class="form-label">New Password:</label>
          <input type="password" class="form-control" placeholder="Enter new password" name="password" required>
        </div>

        <div class="form-group mb-2">
          <label for="password_confirmation" class="form-label">Confirm New Password:</label>
          <input type="password" class="form-control" placeholder="Confirm new password" name="password_confirmation" required>
        </div>

        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 