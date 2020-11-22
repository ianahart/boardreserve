@extends('layouts.layout')

@section('content')
<div class="reviews-banner">
  <header>
    <h1>Reviews For The {{ $model }}</h1>
    <p>({{$totalReviews}}) Reviews</p>
    @if(count($reviews) === 0)
    <p>Be The First To Write a Review!</p>
    @endif
    <h1><i class="fas fa-star-half-alt"></i> {{ $avgReviewRating }}/ 10</h1>
    <p>{{ session('delete_review_success') ?? '' }}</p>
    <p>{{ session('update_review_success') ?? '' }}</p>
  </header>
</div>
@if(count($reviews) !== 0)
<div class="reviews-container">
  @foreach($reviews as $review)
  <div class="single-review">
    @if ($review->edited)
    <p id="edited-identifier">(Edited)</p>
    @endif
    <div class="author">
      <a href="/users/profile/{{$review->userId}}"> <i class="fas fa-user"></i> {{ $review->username }}</a>
      <div>
        @if ($review->userId === $commentOwner)
        <form action="/reviews/{{$review->id}}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="delete-comment"><i class="fas fa-minus-circle"></i> Remove Comment</button>
        </form>
        <a id="edit-comment" href="/reviews/update/{{$review->id}}?model={{$model}}"><i class="far fa-edit"></i> Edit Comment</a>
        @endif
        <p><i class="fas fa-calendar-day"></i> {{ $review->readable_date }}</p>
      </div>
    </div>
    <div class="scale">
      <div data-rating="{{$review->rating}}" class="rating-bar"><i class="fas fa-star star-rating"></i></div>
    </div>
    <p class="review-content">{{ $review->content }}</p>
  </div>

  @endforeach
</div>
@endif
<script src="/js/review.js"></script>
@endsection