@extends('layouts.app')

@section('content')
    @php
        $authed = auth()->user();
    @endphp

    @can('createuser', $authed)
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('users.create') }}">
                    Create user
                </a>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">Users list</div>

        <div class="card-body">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            <div class="d-flex justify-content-end">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="form-group">
                        <label for="deleted" class="col-form-label">Show deleted:</label>
                        <select class="form-control" name="deleted" id="deleted" onchange="this.form.submit()">
                            <option value="false" {{ request('deleted') == 'false' ? 'selected' : '' }}>No</option>
                            <option value="true" {{ request('deleted') == 'true' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </form>
            </div>

            <table class="table table-responsive-sm table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>

                        {{-- @if ($withDeleted)
                        <th>Deleted at</th> --}}
                        {{-- @endif --}}
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->phone_number }}</td>
                            {{-- <td>
                            @foreach ($user->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </td> --}}
                            {{-- @if ($withDeleted)
                            <td>{{ $user->deleted_at ?? 'Not deleted' }}</td>
                        @endif --}}
                            <td>
                                @if ($authed->type != 'admin' && $user->id == $authed->id)
                                    <a class="btn btn-sm btn-info" href="{{ route('users.edit', $user) }}">
                                        Edit
                                    </a>
                                @endif
                                @can('edituser', $authed)
                                    <a class="btn btn-sm btn-info" href="{{ route('users.edit', $user) }}">
                                        Edit
                                    </a>
                                @endcan
                                @can('deleteuser', $authed)
                                    @if ($user->deleted_at == null)

                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                    @else
                                    <form action="{{ route('users.force-delete', $user) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                    @endif
                                </form>
                                @endcan
                                @can('restoreUser',$authed  )

                                @if (!$user->deleted_at==null)
                                <form action="{{ route('users.restore', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    @csrf
                                    <input type="submit" class="btn btn-sm btn-warning" value="Restore">
                                </form>
                                @endif
                            @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $users->withQueryString()->links() }}
        </div>
    </div>
@endsection
