@extends('layouts.layout')

@section('content')
<div class="add-snowboard-hero"></div>
<div class="add-snowboard-form-container">
  <form method="POST" action="/snowboards" enctype="multipart/form-data" class="register-form">
    @csrf
    <h2>Sell Your Snowboard</h2>
    <div class="image-banner">
      <div class="add-snowboard-img-container">
        <img src="/img/add-snowboard.jpg" alt="snowboarding over water" />
      </div>
      <div class="add-snowboard-img-container">
        <img src="/img/add-snowboard-2.jpg" alt="snowboarding in the mountains" />
      </div>
    </div>
    <div class="add-snowboard-divider">
      <div></div>
      <div></div>
    </div>
    <div class="form-content">
      <div class="input-group">
        <label>Brand:</label>
        <input type="text" value="{{old('brand')}}" name="brand" />
      </div>
      <div class="form-error">
        @error('brand')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Model:</label>
        <input type="text" value="{{old('model')}}" name="model" />
      </div>
      <div class="form-error">
        @error('model')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Shape:</label>
        <input type="text" value="{{old('shape')}}" name="shape" />
      </div>
      <div class="form-error">
        @error('shape')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Length:</label>
        <input type="text" value="{{old('length')}}" name="length" />
      </div>
      <div class="form-error">
        @error('length')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Price:</label>
        <input type="text" value="{{old('price')}}" name="price" />
      </div>
      <div class="form-error">
        @error('price')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group-quantity">
        <label>Quantity:</label>
        <input type="text" value="{{old('quantity')}}" name="quantity" />
      </div>
      <div class="form-error">
        @error('quantity')
        {{ $message }}
        @enderror
      </div>
      <div class="input-group">
        <label>Description:</label>
        <textarea type="text" name="desc">{{old('desc')}}
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
        <div class="upload-box">
          <div class="border-box">
            <input id="fileElem" type="file" name="file" />
            <i class="fas fa-check-square upload-complete-icon hidden"></i>
            <i class="fas fa-upload upload-icon"></i>
            <label id="upload-label" for="fileElem">Drag an Image Here</label>
          </div>
        </div>
        <div class="form-error">
          @error('file')
          {{ $message }}
          @enderror
        </div>
      </div>
      <div class="add-snowboard-button-container">
        <button type="submit" name="addsnowboard"><i class="fas fa-plus"></i> Add to Store</button>
      </div>
    </div>
  </form>
</div>
<script src="/js/photoUpload.js"></script>
@endsection