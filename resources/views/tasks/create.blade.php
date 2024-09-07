@extends('layouts.app')

@section('content')
    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">Create project</div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="title">Name</label>
                    <input class="form-control @error('Name')  'is-invalid' '' @enderror" type="text" name="name" id="title" value="{{ old('Name') }}" required>
                    @error('Name')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="description">Description</label>
                    <textarea class="form-control @error('description')  is-invalid '' @enderror" rows="10" name="description" id="description">{{ old('description') }}</textarea>
                    @error('description')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input class="form-control @error('deadline')  is-invalid '' @enderror" type="date" name="deadline" id="deadline" value="{{ old('deadline') }}">
                    @error('deadline')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="user_id">Assigned user</label>
                    <select class="form-control @error('user_id')  is-invalid '' @enderror" name="user_id" id="user_id" required>
                        @foreach($users as $id => $entry)
                            <option value="{{ $entry->id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="client_id">Assigned client</label>
                    <select class="form-control @error('client_id')  is-invalid '' @enderror" name="client_id" id="client_id" required>
                        @foreach($clients as $id => $entry)
                            <option value="{{ $entry->id }}" {{ old('client_id') == $id ? 'selected' : '' }}>{{ $entry->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="project_id">Assigned project</label>
                    <select class="form-control @error('project_id')  is-invalid '' @enderror" name="project_id" id="project_id" required>
                        @foreach($projects as $id => $entry)
                            <option value="{{ $entry->id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry->title }}</option>
                        @endforeach
                    </select>
                    @error('project_id')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status')  is-invalid '' @enderror" name="status" id="status" required>
                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Open</option>
                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Close</option>
                    </select>
                    @error('status')

                    <div class="invalid-feedback">
                        {{ $message  }}
                    </div>
                    @enderror
                    <span class="help-block"> </span>
                </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </div>
        </div>
    </form>

@endsection
