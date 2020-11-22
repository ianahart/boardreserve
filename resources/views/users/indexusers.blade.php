@extends('layouts.layout')

@section('content')
<div class="all-users-banner">
  <div class="all-users-overlay">
    <header>
      <h1>Browse Other Users</h1>
      <p class="now-following">{{ session('now_following') ?? ''}}</p>
      <p class="unfollow-message">{{ session('unfollow') ?? ''}}</p>
      <div class="title-underline"></div>
    </header>
  </div>
</div>
<div class="all-users-container">
  @foreach($users as $user)
  <div class="single-user">
    <div>
      <div class="single-user-img-container">
        @if($user->image)
        <a href="/users/profile/{{$user->id}}">
          <img src="{{Storage::disk('s3')->url($user->image)}}" alt="{{$user->username}}" />
        </a>
        @else
        <a href="/users/profile/{{$user->id}}">
          <img src="/img/no-picture.png" alt="provided picture" />
        </a>
        @endif
        @if (!is_null($current_user->following))
        @if(!in_array($user->id, $current_user->following))
        <div class="single-user-image-overlay"></div>
        @endif
        @endif
      </div>
      <h3>{{ $user->username }}</h3>
    </div>

    @if(is_array($current_user->following) && in_array($user->id, $current_user->following))
    <div class="unfollow-container">
      <button class="single-user-following-button"><i class="fas fa-check"></i> Following</button>
      <form action="/users/unfollow/{{$user->id}}" method="POST">
        @csrf
        @method('PATCH')
        <button class="unfollow-button" type="submit"><i class="fas fa-user-times"></i> Unfollow</button>
      </form>
    </div>
    @else
    <form action="/users/follow/{{$user->id}}" method="POST">
      @csrf
      @method('PATCH')
      <button type="submit" name="add_follower"><i class="fas fa-plus"></i> Follow</button>
    </form>
    @endif
  </div>
  @endforeach
</div>
<div class="user-pagination" data-total-users="{{$total_pages}}">

</div>
<script src="/js/allUsers.js"></script>
@endsection