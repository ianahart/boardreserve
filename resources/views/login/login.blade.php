@extends('layouts.layout')

@section('content')
<div class="form-hero">
  <header>
    <h1>{{ session('password_changed') ?? '' }}</h1>
    <h1 id="expired-token">{{session('token_expired') ?? ''}}</h1>
  </header>
</div>
<div class="login-form-container">
  <form method="POST" action="/login" class="login-form">
    @csrf
    <h2>Login to BoardReserve</h2>
    <div class="form-content">
      @if($errors->any())
      <div class="form-error">
        {{ $errors->first() }}
      </div>
      @endif
      <div class="input-group">
        <label>Email:</label>
        <input type="text" value="" name="email" />
      </div>
      <div class="form-error">
        @error('email')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Password:</label>
        <input type="password" value="" name="password" />
      </div>
      <div class="form-error">
        @error('password')
        {{ $message }}
        @enderror
      </div>
      <div class="not-registered">
        <p>Not registered? <a href="/register/create">Create an Account</a></p>
      </div>
      <div class="forgot-password-link">
        <a href="/forgotpassword">Forgot Password</a>
      </div>
      <div class="login-button-container">
        <button type="submit" name="register"><i class="fas fa-key"></i> Login</button>
      </div>
    </div>
  </form>
</div>
@endsection