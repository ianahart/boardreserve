@extends('layouts.layout')

@section('content')

<div class="myboards-banner">
  <div class="myboards-banner-overlay">
    <header>
      <h2>{{session('success')}}</h2>
      <h2>{{ session('update_success') }}</h2>
      <h1>Snowboards that you have for sale, {{ $name }}</h1>
    </header>
    <div class="myboards-banner-underline"></div>
  </div>
</div>
<div class="myboards-container">
  <h3 class="estimated-total">Estimated Worth: ${{ $potentialSaleTotal }}</h3>
  <div class="seller-snowboard-grid">
    @foreach($snowboards as $snowboard)
    <div class="seller-snowboard">
      <div class="seller-details">
        <h3>{{ $snowboard->brand }}</h3>
        <i class="fas fa-times"></i>
        <h4> {{ $snowboard->model }}</h4>
      </div>
      @if (!$snowboard->image)
      <a href="/snowboards/{{$snowboard->id}}"><img src="/img/no-picture-2.png" alt="no snowboard picture provided" /></a>
      @else
      <a href="/snowboards/{{ $snowboard->id }}"><img src="{{Storage::disk('s3')->url($snowboard->image)}}" alt="{{$snowboard->model}}" /></a>
      @endif
    </div>
    <div class="seller-snowboard-forms-container">
      <div class="snowboard-delete-modal hidden">
        <p class="modal-question">You have <span>{{ $snowboard->quantity }}</span> of the <span>{{ $snowboard->model }}</span> board</p>
        <form id="delete-snowboard-form" action="/users/snowboards/{{$snowboard->id}}" method="POST">
          @csrf
          @method('DELETE')
          <div class="form-error">
            @if($snowboard->id == session('formID'))
            @error('num_to_delete')
            <p>{{ $message }}</p>
            @enderror
            @endif
          </div>
          <input name="num_to_delete" placeholder="How many to remove..." />
          <input name="max" type="hidden" value="{{$snowboard->quantity}}" />
          <div class="modal-buttons">
            <button id="confirm-delete" type="submit" name="delete">Confirm</button>
            <div class="cancel-modal">Cancel</div>
          </div>
        </form>

      </div>
      <form action="/users/snowboards/{{$snowboard->id}}" method="POST">
        @csrf
        @method('UPDATE')
        <a href="/users/snowboards/{{$snowboard->id}}"><i class="fas fa-pen-alt"></i> Update</a>
      </form>
      <button class="seller-delete"><i class="fas fa-trash"></i> Delete</button>
    </div>
    @endforeach
  </div>
</div>
<script src="/js/myBoards.js"></script>
@endsection