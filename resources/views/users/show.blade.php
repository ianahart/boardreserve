@extends('layouts.layout')

@section('content')

<div class="profile-container">
  <div class="profile-information-container">
    <a href="/snowboards/{{$prevPage}}"><i class="fas fa-arrow-left"></i> Back to details</a>
    <div class="profile-information">
      <div class="contact-information">
        <p><i class="fas fa-map-pin"></i> Joined: {{ $joined }}</p>
        <h2><i class="fas fa-user"></i> {{ $user->username }}</h2>
        <h2><i class=" fas fa-envelope"></i> {{ $user->email}}</h2>
      </div>
      <div class="profile-picture-container">
        @if($user->image)
        <img src="{{Storage::disk('s3')->url($user->image)}}" alt="user profile picture" />
        @else
        <img src="/img/no-picture.png" alt="anonymous user picture" />
        @endif
      </div>
    </div>
  </div>
  @if($relatedBoards)
  <div class="profile-banner">
    <h2>Other boards for sale by this user ({{ count($relatedBoards) }})</h2>
    <div class="banner-image-container">
      <img src="/img/profile-banner.jpg" alt="lake and mountains" />
    </div>
  </div>
  <div class="related-boards-container">
    <div class="related-boards">
      @foreach($relatedBoards as $relatedBoard)
      <div class="related-board">
        <a href="/snowboards/{{$relatedBoard->id}}">
          @if($relatedBoard->image)
          <img src="{{Storage::disk('s3')->url($relatedBoard->image)}}" />
          @else
          <img src="/img/no-picture-2.png" alt="default snowboard picture" />
          @endif
        </a>
        <div class="related-board-title">
          <h3>{{ $relatedBoard->brand }}</h3>
          <span>|</span>
          <p>{{ $relatedBoard->model }}</p>
        </div>
        <div class="related-details-button-container">
          <a href="/snowboards/{{$relatedBoard->id}}"><i class="fas fa-file-alt"></i> Board Details</a>
        </div>
      </div>
      @endforeach
      @endif
    </div>
  </div>
</div> @endsection