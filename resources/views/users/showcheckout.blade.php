@extends('layouts.layout')

@section('content')
<div class="checkout-container">
  <div class="checkout-banner">
    <img src="/img/checkout-banner.jpg" alt="credit cards" />
    <div class="checkout-overlay">
      <h1>Checkout</h1>
    </div>
  </div>
  <div class="checkout">
    <div class="checkout-content">
      <h2>Please provide your Credit/DEBT card Information</h2>
      <h3><span>Total:</span> ${{ $cart_total }}</h3>
      <form method="POST" action="/users/checkout?total={{$cart_total}}&purchase={{$purchase_id}}" id="form">
        @csrf
        <div class="checkout-input-group">
          <label>Name on card</label>
          <div class="form-error-invalid">
            {{ session('match_not_found') ?? '' }}
          </div>
          <input id="card-holder-name" type="text">
        </div>
        <!-- Stripe Elements Placeholder -->
        <div id="card-element"></div>

        <button id="card-button">
          <i class="fab fa-cc-stripe"></i> Process Payment
        </button>
      </form>
    </div>
  </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script src="/js/checkout.js"></script>
@endsection