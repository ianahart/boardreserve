@extends('layouts.layout')

@section('content')
<div class="payment-info-banner">
  <header>
    <h1>Please Provide Your Information Below</h1>
  </header>
</div>
<div class="payment-info-container">
  <form action="/payment/userpaymentinfo" method="POST">
    @csrf
    <input type="hidden" value="{{$cart_total}}" name="total" />
    <h3>Contact Information:</h3>
    <div class="contact-info">
      <div class="paymentinfo-input-group-split">
        <div class="firstname">
          <label>First Name:</label>
          <input type="text" name="firstname" value="{{$first_name}}" />
          @error('firstname')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="lastname">
          <label>Last Name:</label>
          <input type="text" name="lastname" value="{{$last_name}}" />
          @error('lastname')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="paymentinfo-input-group">
        <label>Email Address:</label>
        <input type="text" name="email" value="{{$email}}" />
        @error('email')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
    </div>
    <h3>Billing Information:</h3>
    <div class="billing-info">
      <div id="country-postal" class="paymentinfo-input-group-split">
        <div class="country">
          <label>Country</label>
          <input class="country-input" type="text" value="{{old('billing_country')}}" name="billing_country" />
          @error('email')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="postal-code">
          <label>Postal Code</label>
          <input type="text" value="{{old('billing_postal_code')}}" name="billing_postal_code" />
          @error('billing_postal_code')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="paymentinfo-input-group-split">
        <div class="city">
          <label>City:</label>
          <input type="text" value="{{old('billing_city')}}" name="billing_city" />
          @error('billing_city')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="state hidden">
          <label>State:</label>
          <input type="text" value="{{old('billing_state')}}" name="billing_state" />
          @error('billing_state')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="paymentinfo-input-group">
        <label>Street Address:</label>
        <input type="text" value="{{old('billing_street_address')}}" name="billing_street_address" />
        @error('billing_street_address')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
    </div>
    <h3>Shipping Information:</h3>
    <div class="checkbox">
      <input type="checkbox" id="checkbox" />
      <label>Use Billing Information</label>
    </div>
    <div class="billing-info">
      <div id="country-postal" class="paymentinfo-input-group-split">
        <div class="country">
          <label>Country</label>
          <input class="country-input" value="{{old('shipping_country')}}" type="text" name="shipping_country" />
          @error('shipping_country')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="postal-code">
          <label>Postal Code</label>
          <input type="text" value="{{old('shipping_postal_code')}}" name="shipping_postal_code" />
          @error('shipping_postal_code')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="paymentinfo-input-group-split">
        <div class="city">
          <label>City:</label>
          <input type="text" value="{{old('shipping_city')}}" name="shipping_city" />
          @error('shipping_city')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="state hidden">
          <label>State:</label>
          <input type="text" value="{{old('shipping_state')}}" name="shipping_state" />
          @error('shipping_state')
          <div class="form-error">
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>
      <div class="paymentinfo-input-group">
        <label>Street Address:</label>
        <input type="text" value="{{old('shipping_street_address')}}" name="shipping_street_address" />
        @error('shipping_street_address')
        <div class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>
    </div>
    <h3>Shipping Method:</h3>
    <div class="shipping-method">
      <div class="method-input-group">
        <label>Standard (7 Business Days) <span>$2.99</span></label>
        <input type="radio" checked name="shipping_method" value="7 day" />
      </div>
      <div class="method-input-group">
        <label>3-5 Day Shipping <span>$6.99</span></label>
        <input type="radio" name="shipping_method" value="3-5 day" />
      </div>
      <div class="method-input-group">
        <label>Next Day Shipping <span>$12.95</span></label>
        <input type="radio" name="shipping_method" value="1 day" />
      </div>
    </div>
    <div class="payment-button-container">
      <button type="submit"><i class="fas fa-forward"></i> Proceed to Payment</button>
    </div>
  </form>
</div>
<script src="/js/paymentInfo.js"></script>
@endsection