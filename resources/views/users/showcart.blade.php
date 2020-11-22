@extends('layouts.layout')

@section('content')
<div>
  <div class="cart-banner">
    <h2>Here Is Your Shopping Cart</h2>
    @if(session()->has('user_message'))
    <p>{{ session('user_message') }}</p>
    @endif
  </div>
  @isset($shopping_cart)
  @if (count($shopping_cart) !== 0)
  <div class="shopping-cart-container">
    <div class="shopping-cart">
      <p class="num-of-items">({{ count($shopping_cart) }}) Items</p>
      <div class="success-delete">
        @if(session('status'))
        <i class="fas fa-check-circle"></i>{{ session('status') }}
      </div>
      @endif
      @foreach($shopping_cart as $item)
      <div class="cart-item-container">
        <form action="/users/cart/{{$item->id}}" method="POST">
          @csrf
          @method('DELETE')
          <input type="hidden" value="{{$item->id}}" name="boardtodelete" />
          <button type="submit"><i class="fas fa-minus-circle"></i> Remove Item</button>
        </form>
        <div class="cart-item">
          <div class="cart-item-information">
            <h3>Brand: {{ $item->brand }}</h3>
            <p>Model: {{ $item->model }}</p>

            <p>Price: <span>${{ $item->price }}</span></p>
          </div>
          <div class="cart-item-image-container">
            @if(!$item->image)
            <img src="/img/no-picture-2.png" alt="a default snowboard" />
            @else
            <img src="{{Storage::disk('s3')->url($item->image)}}" alt="snowboard" />
            @endif
          </div>
        </div>
      </div>
      @endforeach
      <div class="cart-actions-container">
        <div>
          <!-- TURN INTO FORM -->
          <a href="/payment/userpaymentinfo?total={{$cart_total}}"><i class="fas fa-shopping-bag"></i> Checkout</a>
          <a href="/snowboards"> Keep Shopping</a>
        </div>
        <div>
          <p class="cart-total">Total: <span>${{ $cart_total }}</span></p>
        </div>
      </div>
    </div>
    @endisset
  </div>
  @else
  <div class="shopping-cart-empty">
    <p class="shopping-cart-empty-message">{{ $empty_message ?? '' }}</p>
  </div>
  @endif
  @endsection