@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="text-center">Check Your Email</h1>

        <p>Please check your email and verify that</p>

        <p>Best regards,</p>
        <p>Your App Name</p>
        <form method="POST" action="{{ route('verify') }}">
            @csrf

            <div class="form-group row">


            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Resend') }}
                    </button>
                </div>
            </div>
        </form>
@endsection
