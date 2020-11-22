@extends('layouts.layout')

@section('content')
<div class="add-review-banner">
  <h1>Add a Review For The {{ $model }}</h1>
</div>
<div class="review-form-container">
  <form method="POST" action="/reviews/create">
    @csrf
    <div class="form-content">
      <p class="duplicate-error">{{ session('duplicateError') ?? '' }}</p>
      @if (session('duplicateError'))
      <a class="back-to-details-link" href="/snowboards/{{$snowboard}}"><i class="fas fa-arrow-left"></i> Return to details</a>
      @endif
      <div class="review-form-img-container">
        <img src="/img/review.jpg" alt="review picture" />
      </div>
      <div class="input-group">
        <label>Content:</label>
        <textarea name="content"></textarea>
      </div>
      <div class="form-error">
        @error('content')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Rating: (1-10)</label>
        <label>Selected:<span id="current-rating">1</span></label>
        <input id="slider" type="range" value="" min="1" max="10" name="rating" />
      </div>
      <div class="form-error">
        @error('rating')
        {{ $message }}
        @enderror
      </div>
      <input type="hidden" value="{{$snowboard}}" name="boardId" />
      <div class="addreview-button-container">
        <button type="submit" name="register"><i class="fas fa-plus"></i> Add</button>
      </div>
    </div>
  </form>
</div>
<script src="/js/review.js"></script>
@endsection