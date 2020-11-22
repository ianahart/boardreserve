@extends('layouts.layout')

@section('content')
<div class="forgot-password-banner">
  <header>
    <h1>Reset Password</h1>
  </header>
</div>
<div class="forgot-password-container">
  <form action="/forgotpassword" method="POST">
    @csrf
    <p>Please enter your <span>email address</span> so we can send you a reset link</p>
    <div class="forgot-password-form-content">
      <div class="input-group">
        <label>Email:</label>
        <input type="text" name="email" />
        <div class="form-error">
          {{ session('not_found') ?? '' }}
        </div>
        @error('email')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="forgot-password-btn-container">
        <button type="submit"><i class="far fa-paper-plane"></i> Send</button>
      </div>
    </div>
  </form>
</div>
@endsection