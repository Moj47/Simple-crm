@extends('layouts.app')

@section('content')
    <form action="{{ route('clients.update',$client->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">Client information</div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="contact_name">Name</label>
                    <input class="form-control @error('name') is-invalid @enderror '' " type="text" name="name" id="name" value="{{ old('name',$client->name) }}" required>
                    @error( 'name')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror ''" type="text" name="email" id="email" value="{{old('email',$client->email)}}"  required>
                    @error('email')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="contact_phone_number">Phone</label>
                    <input class="form-control @error('phone') is-invalid @enderror ''" type="text" name="phone" id="phone" value="{{ old('phone',$client->phone) }}">
                    @error('phone')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>
                <div class="form-group">
                    <label for="contact_phone_number">Address</label>
                    <input class="form-control @error('address') is-invalid @enderror ''" type="text" name="address" id="address" value="{{ old('address',$client->address) }}">
                    @error('address')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>
            </div>
        </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
    </form>

@endsection
