@extends('layouts.layout')

@section('content')
<div class="shop-success-container">
  <header>
    <h1>{{ session('success') }}</h1>
    <h3>{{session('email_sent')}}</h3>
    <div class="success-img-container">
      <img src="/img/thanksshopping.png" alt="snowboarder doing a grab" />
    </div>
    <a href="/">Return to Homepage</a>
  </header>
</div>
@endsection