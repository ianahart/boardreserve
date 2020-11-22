@extends('layouts.layout')

@section('content')
<div class="change-password-banner">
  <header>
    <h1>Change Your Password</h1>
    <div class="title-underline"></div>
  </header>
</div>
<div class="change-password-form-container">
  <form action="/users/changepassword/{{$userId}}" method="POST">
    @csrf
    @method('PATCH')
    <div class="form-banner"></div>
    <div class="change-password-img-container">
      <img src="/img/changepassword.jpg" alt="lake and mountains" />
    </div>
    <div class="change-password-form-content">
      <div class="main-password-errors">
        {{session('password_invalid') ?? ''}}
        {{ session('password_same') ?? '' }}
        {{ session('new_and_confirm_not_match') ?? '' }}
      </div>
      <div class="input-group-password">
        <label>Old Password:</label>
        <input type="password" name="oldpassword" />
        @error('oldpassword')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="input-group-password">
        <label>New Password:</label>
        <input type="password" name="newpassword" />
        @error('newpassword')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="input-group-password">
        <label>Confirm New Password:</label>
        <input type="password" name="confirmnewpassword" />
        @error('confirmnewpassword')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
    </div>
    <div class="change-password-button-container">
      <button type="submit"><i class="fas fa-key"></i> Update Password</button>
    </div>
  </form>
</div>

@endsection