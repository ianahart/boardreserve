@extends('layouts.layout')

@section('content')
<div class="reset-password-banner">
  <header>
    <h1>Reset Your Password</h1>
  </header>
</div>

<div class="reset-password-form-container">
  <form action="/resetpassword?token={{$token}}" method="POST">
    @csrf
    @method('PATCH')
    <h2>Add Your New Password:</h2>
    <div class="reset-form-content">
      <div class="input-group">
        <label>New Password:</label>
        <input type="password" name="password" />
        <div class="form-error">
          {{ session('invalid') ?? '' }}
        </div>
        @error('password')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="input-group">
        <label>Confirm New Password:</label>
        <input type="password" name="confirmpassword" />
        <div class="form-error">
          {{ session('not_same_password') ?? '' }}
        </div>
      </div>
      <div class="reset-password-btn-container">
        <button type="submit"><i class="fas fa-sync"></i> Reset Password</button>
      </div>
    </div>
  </form>
</div>
@endsection