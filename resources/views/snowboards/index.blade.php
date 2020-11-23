@extends('layouts.layout')

@section('content')
<div class="snowboard-shop-container">
  <div></div>
  <div class="shop-banner">
    <div class="shop-overlay">
      <div class="shop-overlay-content">
        <h3>{{ session('success') }}</h3>
        <h1 class="shop-heading">Shop Your Favorite Brands</h1>
        <h3 class="shop-subheading">Featured Board:</h3>
        <div class="shop-heading-divider"></div>
        <img class="featured-board-image" src="/img/shop-header.png" alt="featured-board" />
      </div>
    </div>
  </div>
  <div class="inventory-filter-container">
    <form action="/snowboards?selected=" method="POST">
      @csrf
      <select name="sort">
        <option value="alphabetical asc" @if( session('selected_value')==='alphabetical asc' ) selected="selected" @endif>A-Z by Brand</option>
        <option value="alphabetical desc" @if( session('selected_value')==='alphabetical desc' ) selected="selected" @endif>Z-A by Brand</option>
        <option value="> 500" @if( session('selected_value')==='> 500' ) selected="selected" @endif>Over than $500.00</option>
        <option value="< 500" @if( session('selected_value')==='< 500' ) selected="selected" @endif>Less than $500.00</option>
        <option value="new" @if( session('selected_value')==='new' ) selected="selected" @endif>New Arrivals</option>
      </select>
      <i class="fas fa-sort"></i>

      <button type="submit"><i class="fas fa-filter"></i> Filter</button>
    </form>
  </div>
  <div class="inventory">
    @if(isset(session('snowboards')))

    @foreach(session('snowboards') as $snowboard)
    <div class="snowboard">
      @if($snowboard->image)
      <a href="/snowboards/{{$snowboard->id}}"><img class="shop-snowboard-image" src="{{Storage::disk('s3')->url($snowboard->image)}}"></a>
      @else
      <a href="/snowboards/{{$snowboard->id}}"><img class="shop-snowboard-image" src="/img/no-picture-2.png" /></a>
      @endif
      <div class="shop-board-stats">
        <h3>{{ $snowboard->brand }}</h3>
        <span>|</span>
        <p>{{ $snowboard->model }}</p>
      </div>

      <div class="shop-actions-container">
        @if(session('userID') !== $snowboard->seller)
        <form action="/users/cart" method="POST">
          @csrf
          <input type="hidden" value="{{$snowboard->id}}" name="board" />
          @if ($snowboard->quantity !== 0)
          <button type="submit"><i class="fas fa-cart-plus"></i> Add to cart</button>
          @else
          <div class="out-of-stock">Out of Stock</div>
          @endif
        </form>
        @else
        <div class="owner-button"><i class="fas fa-wallet"></i> Owner</div>
        @endif
        <a href="/snowboards/{{$snowboard->id}}"><i class="fas fa-file-alt"></i> Board Details</a>
      </div>
    </div>
    @endforeach
    @else
    @foreach($snowboards as $snowboard)
    <div class="snowboard">
      @if($snowboard->image)
      <a href="/snowboards/{{$snowboard->id}}"><img class="shop-snowboard-image" src="{{Storage::disk('s3')->url($snowboard->image)}}"></a>
      @else
      <a href="/snowboards/{{$snowboard->id}}"><img class="shop-snowboard-image" src="/img/no-picture-2.png" /></a>
      @endif
      <div class="shop-board-stats">
        <h3>{{ $snowboard->brand }}</h3>
        <span>|</span>
        <p>{{ $snowboard->model }}</p>
      </div>

      <div class="shop-actions-container">
        @if(session('userID') !== $snowboard->seller)
        <form action="/users/cart" method="POST">
          @csrf
          <input type="hidden" value="{{$snowboard->id}}" name="board" />
          @if ($snowboard->quantity !== 0)
          <button type="submit"><i class="fas fa-cart-plus"></i> Add to cart</button>
          @else
          <div class="out-of-stock">Out of Stock</div>
          @endif
        </form>
        @else
        <div class="owner-button"><i class="fas fa-wallet"></i> Owner</div>
        @endif
        <a href="/snowboards/{{$snowboard->id}}"><i class="fas fa-file-alt"></i> Board Details</a>
      </div>
    </div>
    @endforeach
    @endif
  </div>
</div>
<script src="/js/inventory.js"></script>

@endsection