@extends('layouts.layout')

@section('content')
<div class="feed-banner">
  <div class="feed-overlay">
    <header>
      <h1>{{ $first_name }}'s Feed</h1>
      <div class="title-underline"></div>
    </header>
  </div>
</div>
<div class="feed-container">
  <div class="feed-container-header"></div>
  @foreach($snowboards as $snowboard)
  <div class="feed-snowboard">
    <div class="feed-snowboard-top">
      <div class="feed-snowboard-header">
        <p class="feed-date">{{$snowboard->readable_date}}</p>
        @if ($snowboard->profilepic)
        <a href="/users/profile/{{$snowboard->seller}}">
          <img class="feed-profilepic" src="{{Storage::disk('s3')->url($snowboard->profilepic)}}" />
        </a>
        @else
        <a href="/users/profile/{{$snowboard->seller}}"><img class="feed-profilepic" src="/img/no-picture.png" /></a>
        @endif
        <a class="feed-username-link" href="/users/profile/{{$snowboard->seller}}">
          <p class="feed-username">{{ $snowboard->username }}</p>
        </a>
        <p class="feed-price">${{ $snowboard->price }}</p>
      </div>
      <div class="feed-snowboard-title">
        <h3>{{ $snowboard->brand }} || {{$snowboard->model}}</h3>
      </div>
    </div>
    <div class="feed-snowboard-bottom">
      @if ($snowboard->image)
      <a href="/snowboards/{{$snowboard->id}}">
        <img class="feed-snowboard-pic" src="{{Storage::disk('s3')->url($snowboard->image)}}" />
      </a>
      @else
      <a href="/snowboards/{{$snowboard->id}}">
        <img class="feed-snowboard-pic" src="/img/no-picture-2.png" />
      </a>
      @endif
    </div>
    <form class="feed-form" action="/users/cart" method="POST">
      @csrf
      <input type="hidden" value="{{$snowboard->id}}" name="board" />
      @if ($snowboard->quantity !== 0)
      <button type="submit"><i class="fas fa-cart-plus"></i> Buy</button>
      @else
      <div class="out-of-stock">Out of Stock</div>
      @endif
    </form>
  </div>
  @endforeach
</div>
</div>
@endsection