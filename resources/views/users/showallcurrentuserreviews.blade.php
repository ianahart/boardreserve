@extends('layouts.layout')

@section('content')
<div class="all-reviews-banner">
  <header>
    <h1>All Your Reviews</h1>
  </header>
</div>

<div class="all-reviews-container">
  <header>
    <h1>Reviews ({{ $total_reviews }})</h1>
  </header>
  <div class="all-reviews">
    @foreach($reviews as $review)
    <div class="singular-review">
      <div class="singular-review-top">
        <div>
          <h2>{{$review->brand }} <span>||</span> {{$review->model}}</h2>
        </div>
        <p class="singular-review-rating"><i class="fas fa-star-half-alt"></i> {{ $review->rating }}</p>
      </div>
      <p class="singular-review-date"><i class="fas fa-calendar-times"></i> {{ $review->readable_date }}</p>
      <p class="singular-review-content">{{ $review->content }}</p>
    </div>

    @endforeach
  </div>
</div>
<div class="review-pagination" data-pages="{{$num_of_pages}}">

</div>
<script src="/js/allReviews.js"></script>
@endsection