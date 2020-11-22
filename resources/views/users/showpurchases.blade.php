@extends('layouts.layout')

@section('content')
<div class="purchases-banner">
  <div class="purchases-overlay">
    @if($snowboards)
    <h1>My Recent Purchases ({{ count($snowboards) }})</h1>
    @else
    <h1>My Recent Purchases (0)</h1>
    @endif
    <div class="title-underline"></div>
  </div>
</div>
<div class="purchases-container">
  <div class="purchases">
    @if($snowboards)
    @foreach($snowboards as $snowboard)
    <div class="purchase">
      <div class="purchase-img-container">
        @if ($snowboard->image)
        <a href="/snowboards/{{ $snowboard->id }}"><img src="{{Storage::disk('s3')->url($snowboard->image)}}" alt="{{$snowboard->model}}" /></a>
        @else
        <img src="/img/no-picture-2.png" alt="no picture provided" />
        @endif
      </div>
      <p>*Purchased on {{ $snowboard->purchase_date }}</p>
      <div class="purchase-info">
        <h2><span>Brand: </span>{{ $snowboard->brand }}</h2>
        <h3><span>Model: </span>{{ $snowboard->model }}</h3>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
@endsection