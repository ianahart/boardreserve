@extends('layouts.layout')

@section('content')
<div class="form-hero"></div>
<div class="register-form-container">
  <form method="POST" action="/register" class="register-form" enctype="multipart/form-data">
    @csrf
    <h2>Create Your Account</h2>
    <div class="register-img-container">
      <img src="/img/register-2.jpg" alt="mountains" />
    </div>
    <div class="form-content">
      <div class="input-group">
        <label>Full Name:</label>
        <input type="text" value="{{old('fullname')}}" name="fullname" />
      </div>
      <div class="form-error">
        @error('fullname')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Desired Username:</label>
        <input type="text" value="{{old('username')}}" name="username" />
      </div>
      <div class="form-error">
        @error('username')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Email:</label>
        <input type="text" value="{{old('email')}}" name="email" />
      </div>
      <div class="form-error">
        @error('email')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Password:</label>
        <input type="password" value="{{old('password')}}" name="password" />
      </div>
      <div class="form-error">
        @error('password')
        {{ $message }}
        @enderror
      </div>
      <div class="file-upload-container">
        <label>Upload a Profile Photo</label>
        <div class="upload-box">
          <div class="border-box">
            <input id="fileElem" type="file" name="file" />
            <i class="fas fa-check-square upload-complete-icon hidden"></i>
            <i class="fas fa-upload upload-icon"></i>
            <label id="upload-label" for="fileElem">Drag an Image Here</label>
          </div>
        </div>
      </div>
      <div class="register-button-container">
        <button type="submit" name="register"><i class="fas fa-user-plus"></i> Register</button>
      </div>
    </div>
  </form>
</div>
<script src="/js/photoUpload.js"></script>
@endsection