@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">Edit project</div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="required" for="title">Title</label>
                            <input class="form-control @error('title') is-invalid @enderror '' }}" type="text"
                                   name="title" id="title" value="{{ old('title', $project->title) }}" required>
                                   @error('title')
                                   <div class="invalid-feedback">
                                       {{ $message }}
                                   </div>
                               @enderror
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label class="required" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror '' }}"
                                      rows="10" name="description"
                                      id="description">{{ old('description', $project->description) }}</textarea>
                                      @error('description')
                                      <div class="invalid-feedback">
                                          {{ $message }}
                                      </div>
                                  @enderror
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input class="form-control @error('deadline') is-invalid @enderror '' }}" type="date"
                                   name="deadline" id="deadline" value="{{ old('deadline', $project->deadline) }}">
                                   @error('deadline')
                                   <div class="invalid-feedback">
                                       {{ $message }}
                                   </div>
                               @enderror
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label for="user_id">Assigned user</label>
                            <select class="form-control @error('user_id') is-invalid @enderror '' "
                                    name="user_id" id="user_id" required>
                                @foreach($users as $entry)
                                    <option
                                        value="{{ $entry->id }}" {{ (old('user_id') ? old('user_id') : $project->user->id ?? '') == $entry->id ? 'selected' : '' }}>{{ $entry->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                            <span class="help-block"> </span>
                        </div>

                        <div class="form-group">
                            <label for="client_id">Assigned client</label>
                            <select class="form-control  @error('client_id') is-invalid @enderror "
                                    name="client_id" id="client_id" required>
                                @foreach($clients as $id => $entry)
                                    <option
                                        value="{{ $project->client->id }}" {{ (old('client_id') ? old('client_id') : $project->client->id ?? '') == $id ? 'selected' : '' }}>{{ $entry->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                            <span class="help-block"> </span>
                        </div>

                            {{-- <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status"
                                        id="status" required>
                                    @foreach(App\Models\Project::STATUS as $status)
                                        <option
                                            value="{{ $status }}" {{ (old('status') ? old('status') : $project->status ?? '') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('status'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('status') }}
                                    </div>
                                @endif
                                <span class="help-block"> </span>
                            </div> --}}

                        <button class="btn btn-primary" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- <div class="col-md-4">
            <div class="card">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <form action="{{ route('media.upload', ['Project', $project]) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label class="required" for="file">File</label>
                            <input class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" type="file"
                                   name="file" id="file">
                            @if($errors->has('file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('file') }}
                                </div>
                            @endif
                            <span class="help-block"> </span>
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Upload
                        </button>
                    </form>

                    <table class="table mt-4">
                        <thead>
                        <tr>
                            <th scope="col">File name</th>
                            <th scope="col">Size</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->getMedia() as $media)
                            <tr>
                                <th scope="row">{{ $media->file_name }}</th>
                                <td>{{ $media->human_readable_size }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" href="{{ route('media.download', $media) }}">
                                        Download
                                    </a>
                                    <form action="{{ route('media.delete', ['Project', $project, $media]) }}"
                                          method="POST" onsubmit="return confirm('Are your sure?');"
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

@endsection
