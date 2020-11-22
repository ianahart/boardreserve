@extends('layouts.layout')

@section('content')
<div class="pending-message">
  <h3><i class="far fa-check-circle"></i> Email has been successfully sent</h3>
  <div class="pending-message-content">
    <p><span>{{ $name }}</span>, a password reset link has been sent to: <span>{{ $email }}</span></p>
  </div>
</div>
@endsection