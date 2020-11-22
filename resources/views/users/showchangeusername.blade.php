@extends('layouts.layout')

@section('content')
<div class="change-username-banner">
  <header>
    <h1>Change Username:</h1>
    <div class="title-underline"></div>
  </header>
</div>
<div class="change-username-container">
  <form action="/users/changeusername/{{$user->id}}" method="POST">
    @csrf
    @method('PATCH')
    <div class="username-form-header"></div>
    <div class="change-username-img-container">
      <img src="/img/register-2.jpg" alt="lakes and mountains" />
      <div class="username-overlay"></div>
    </div>
    <div class="current-username-title">
      <p><span>Current Username: </span>{{ $user->username }}</p>
    </div>
    <div class="username-form-content">
      <div class="username-input-group">
        <label>Change Username:</label>
        <input type="text" name="username" />
        <div class="form-error">
          {{ session('username_taken') ?? '' }}
          {{ session('time') ?? '' }}
        </div>
        @error('username')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="change-username-btn-container">
        <button type="submit"><i class="fas fa-key"></i> Update Username</button>
      </div>
    </div>
  </form>
</div>
@endsection