@extends('layouts.layout')

@section('content')
<div class="update-snowboard-form-container">
  <form method="POST" action="/users/snowboards/{{$snowboard->id}}" enctype="multipart/form-data" class="register-form">
    @csrf
    @method('PATCH')
    <h2>Update The <span>{{ $snowboard->brand }}</span> | <span>{{$snowboard->model}}</span></h2>
    <div class="image-banner">
      <div class="add-snowboard-img-container">
        <img src="/img/update_form.jpg" alt="snowboarding over water" />
      </div>
      <div class="form-content">
        <div class="input-group">
          <label>Brand:</label>
          <input type="text" value="{{$snowboard->brand}}" name="brand" />
        </div>
        <div class="form-error">
          @error('brand')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Model:</label>
          <input type="text" value="{{$snowboard->model}}" name="model" />
        </div>
        <div class="form-error">
          @error('model')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Shape:</label>
          <input type="text" value="{{$snowboard->shape}}" name="shape" />
        </div>
        <div class="form-error">
          @error('shape')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Length:</label>
          <input type="text" value="{{$snowboard->size}}" name="length" />
        </div>
        <div class="form-error">
          @error('length')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Price:</label>
          <input type="text" value="{{$snowboard->price}}" name="price" />
        </div>
        <div class="form-error">
          @error('price')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group-quantity">
          <label>Quantity:</label>
          <input type="text" value="{{$snowboard->quantity}}" name="quantity" />
        </div>
        <div class="form-error">
          @error('quantity')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Description:</label>
          <textarea type="text" name="desc">{{$snowboard->desc}}
          </textarea>
        </div>
        <div class="form-error">
          @error('desc')
          {{ $message }}
          @enderror
        </div>
        <div class="input-group">
          <label>Choose a Category:</label>
          <select name="category">
            <option value="all-mountain snowboarding">All-Mountain Snowboarding</option>
            <option value="park & freestyle snowboarding">Park & Freestyle Snowboarding</option>
            <option value="freeride/powder snowboarding">Freeride/Powder Snowboarding</option>
          </select>
        </div>
        <div class="form-error">
          @error('category')
          {{ $message }}
          @enderror
        </div>
        <div class="file-upload-container">
          <label>Upload a Photo</label>
          @if ($prevImage !== '')
          <a class="download-link" href="{{$prevImage}}" download><i class="fas fa-image"></i> Download Previous</a>
          @endif
          <div class="upload-box">
            <div class="border-box">
              <input id="fileElem" type="file" name="file" />
              <i class="fas fa-check-square upload-complete-icon hidden"></i>
              <i class="fas fa-upload upload-icon"></i>
              <label id="upload-label" for="fileElem">Drag an Image Here</label>
            </div>
          </div>
        </div>
        <div class="add-snowboard-button-container">
          <button type="submit" name="addsnowboard"><i class="fas fa-pen-square"></i> Confirm Update</button>
        </div>
      </div>
  </form>
</div>
<script src="/js/photoUpload.js"></script>
@endsection