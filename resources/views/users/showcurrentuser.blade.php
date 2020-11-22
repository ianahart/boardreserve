@extends('layouts.layout')

@section('content')
<div class="me-profile">
  <div class="account-settings-trigger">
    <p><i class="fas fa-arrow-up nav-trigger-arrow"></i> Account Settings</p>
  </div>
  <div id="link-container" class="user-links-container  hidden">
    <div class="user-links-header">
      <h2> <i class="fas fa-user"></i> Your Account</h2>
    </div>
    <nav class="user-links">
      <ul>
        <div>
          <li><a href="/users/purchases"><i class="fas fa-shopping-bag"></i> My Recent Purchases</a></li>
          <li><a href="/users/reviews?page=1"><i class="fas fa-scroll"></i> My Reviews</a></li>
          <li><a href="/users/snowboards"><i class="fas fa-snowboarding"></i> My Snowboards</a></li>
        </div>
        <div>
          <li><a href="/users/changeusername/{{$user->id}}"><i class="fas fa-cog"></i> Change Username</a></li>
          <li><a href="/users/changepassword/{{$user->id}}"><i class="fas fa-cog"></i> Change Password</a></li>
        </div>
      </ul>
    </nav>
  </div>
  <div class="me-profile-information-container">
    <h2>{{ session('password_updated')?? '' }}</h2>
    <h2>{{ session('update_name_success') ?? '' }}</h2>
    <div class="me-profile-background">
      @if($user->image)
      <div class="me-profile-img-container">
        <img src="{{Storage::disk('s3')->url($user->image)}}" alt="a headshot of {{$user->name}}" />
      </div>
      @else
      <div class="me-profile-img-container">
        <img src="/img/no-picture.png" alt="anonymous user picture" />
      </div>
      @endif
      <div class="name-date">
        <p><span>Name:</span> {{ $user->name }} </p>
        <p><span>Joined:</span> {{ $joinedDate }}</p>
      </div>
      <div class="display-name">
        <p><span>Display Name:</span> {{ $user->username }}</p>
      </div>
      <div class="follower-count">
        @if (is_array($user->followers))
        <p><span>Followers:</span>{{ count($user->followers)}}</p>
        @else
        <p><span>Followers:</span>0</p>
        @endif
      </div>
    </div>
  </div>
</div>
<script src="/js/userProfile.js"></script>
@endsection