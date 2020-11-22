@extends('layouts.layout')

@section('content')
<div class="following-banner">
  <div class="following-overlay">
    <header>
      <h1>Users You Are Following</h1>
      <p class="unfollow-message">{{ session('unfollowed') }}</p>
    </header>
  </div>
</div>
<div class="following-container">
  @if(!is_null($following_users))
  @foreach($following_users as $following_user)
  <div class="following-user">
    <div class="following-left">
      @if($following_user->image)
      <a href="/users/profile/{{$following_user->id}}">
        <img src="{{Storage::disk('s3')->url($following_user->image)}}" alt="{{$following_user->username}}" />
      </a>
      @else
      <a href="/users/profile/{{$following_user->id}}">
        <img src="/img/no-picture.png" alt="provided picture" />
      </a>
      @endif
      <h3 class="following-username">{{ $following_user->username }}</h3>
      <form action="/users/following/{{$following_user->id}}" method="POST">
        @csrf
        @method('PATCH')
        <button class="unfollow-button" type="submit"><i class="fas fa-user-times"></i> Unfollow</button>
      </form>
    </div>
  </div>
  @endforeach
  @endif
</div>
@endsection