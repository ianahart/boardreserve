@extends('layouts.layout')

@section('content')
<div class="edit-review-banner">
  <h1>Edit Comment For The {{ $model }}</h1>
</div>
<div class="review-form-container">
  <form method="POST" action="/reviews/{{$review->id}}">
    @csrf
    @method('PATCH')
    <div class="form-content">
      <div class="review-form-img-container">
        <img src="/img/review.jpg" alt="review picture" />
      </div>
      <div class="input-group">
        <label>Content:</label>
        <textarea name="content">{{ $review->content }}</textarea>
      </div>
      <div class="form-error">
        @error('content')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Rating: (1-10)</label>
        <label>Selected:<span id="current-rating">1</span></label>
        <input id="slider" type="range" value="{{$review->rating}}" min="1" max="10" name="rating" />
      </div>
      <div class="form-error">
        @error('rating')
        {{ $message }}
        @enderror
      </div>
      <div class="editreview-button-container">
        <button type="submit" name="register"><i class="fas fa-edit"></i> Edit</button>
      </div>
    </div>
  </form>
</div>
<script src="/js/review.js"></script>
@endsection