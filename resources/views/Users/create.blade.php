@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Create user</div>

        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input class="form-control @error('name')   is-invalid @enderror '' " type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="email">Email</label>
                    <input class="form-control @error('email')   is-invalid @enderror '' " type="text" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input class="form-control @error('email')   is-invalid @enderror ''" type="text" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone number</label>
                    <input class="form-control @error('phone_number')   is-invalid @enderror ''" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
                    @error('phone_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
