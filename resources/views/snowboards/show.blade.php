@extends('layouts.layout')

@section('content')


<div class="details-container">
  <p class="review-success">{{ session('review_success') ?? '' }}</p>
  <a class="back-to-shop-btn" href="/snowboards"><i class="fas fa-arrow-left"></i> Back to shop</a>
  <div class="details-grid">
    <div class="details-image-container">
      @if($snowboard->image)
      <img src="{{Storage::disk('s3')->url($snowboard->image)}}" />
      @else
      <img src="/img/no-picture-2.png" alt="no snowboard provided" />

      @endif
    </div>
    <div class="details-stats">
      <div class="details-header">
        <div class="details-name">
          <h3>{{ $snowboard->brand }}</h3>
          <i class="fas fa-grip-lines-vertical details-divider-icon"></i>
          <h3>{{ $snowboard->model }}</h3>
        </div>
        <p class="details-quantity">{{ $snowboard->quantity }} in stock </p>
      </div>
      <div class="features-header">
        <h3>Features:</h3>
        <i class="fas fa-plus features-plus-icon"></i>
        <i class="fas fa-minus features-minus-icon hidden"></i>
      </div>
      <div class="details-features hidden-effect">
        <div>
          <h4><i class="fas fa-money-bill-alt"></i> Price</h4>
          <p>${{ $snowboard->price }}</p>
        </div>
        <div>
          <h4><i class="fas fa-award"></i> AVG Review Rating</h4>
          <p>{{ $AVGReviewRating }}/ 10</p>
        </div>
        <div>
          <h4><i class="fas fa-mountain"></i> Snowboards Category</h4>
          <p>{{ $snowboard->category }}</p>
        </div>
        <div>
          <h4><i class="fas fa-shapes"></i> Board Shape</h4>
          <p>{{ $snowboard->shape }}</p>
        </div>
        <div>
          <h4><i class="fas fa-ruler-vertical"></i> Board Size (cm)</h4>
          <p>{{ $snowboard->size }}</p>
        </div>
        <div class="details-seller">
          <h4><i class="fas fa-user"></i> Seller</h4>
          <a href="/users/profile/{{$snowboard->seller}}?board={{$snowboard->id}}">
            {{ $sellerUsername }}
          </a>
        </div>
      </div>
    </div>
    <div class="details-description">
      <p>{{ $snowboard->desc }}</p>
    </div>
    <div class="review-options">

      <a href="/reviews/create?model={{$snowboard->model}}&boardId={{$snowboard->id}}"><i class="fas fa-plus"></i> Add a Review</a>
      <a href="/reviews/{{$snowboard->id}}?board={{$snowboard->model}}"><i class="fab fa-readme"></i> Read Reviews</a>

    </div>
  </div>
</div>
</div>
<script src="/js/boardDetails.js"></script>
@endsection