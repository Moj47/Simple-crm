@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="text-center">Verify Your Email</h1>
        <p>Dear {{ $email }},</p>
        <p>Please click the link below to verify your email address:</p>
        <p>
          <a href="{{ route('click', $email) }}" class="btn btn-primary">
            Verify Email
          </a>
        </p>
        <p>Best regards,</p>
        <p>Your App Name</p>
      </div>
    </div>
  </div>
@endsection
