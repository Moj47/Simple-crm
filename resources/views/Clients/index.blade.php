@extends('layouts.app')

@section('content')
@php
use App\Models\Client;
$client=Client::factory()->make();
@endphp

@can('createClient',$client)
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">

            <a class="btn btn-success" href="{{ route('clients.create') }}">
                Create client
            </a>
        </div>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">Clients list</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <form action="{{ route('clients.index') }}" method="GET">
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->address }}</td>
                        <td>
                            @can('editClient',$client)

                            <a class="btn btn-xs btn-info" href="{{ route('clients.edit', $client) }}">
                                Edit
                            </a>
                            @endcan
                            @can('deleteclient', $client)
                                    @if ($client->deleted_at == null)

                                    <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    @else
                                    <form action="{{ route('clients.force-delete', $client->id) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    @endif
                                </form>
                                @endcan
                                @if ($client->deleted_at != null)
                                @can('restoreClient', $client)
                                <form action="{{ route('clients.restore', $client->id) }}" method="POST"
                                    onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    @csrf
                                    <input type="submit" class="btn btn-xs btn-warning" value="Restore">
                                </form>
                                @endcan
                                @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $clients->withQueryString()->links() }}
        </div>
    </div>

@endsection
