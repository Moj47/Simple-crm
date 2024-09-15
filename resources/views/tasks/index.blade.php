@extends('layouts.app')

@section('content')
@php
use App\Models\Task;
$task=Task::factory()->make();
@endphp
    <div style="margin-bottom: 10px;" class="row">

        @can('createtask',$task)

        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('tasks.create') }}">
                Create task
            </a>
        </div>
        @endcan

    </div>

    <div class="card">
        <div class="card-header">Tasks list</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <form action="{{ route('tasks.index') }}" method="GET">
                    <div class="form-group row">
                        <label for="status" class="col-form-label">Status:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                {{-- @foreach(App\Models\Task::STATUS as $status)
                                    <option
                                        value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <table class="table table-responsive-sm table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Client</th>
                    <th>Assigned to</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->client->name }}</td>
                        <td>{{ $task->user->name }}</td>
                        <td>{{ $task->deadline }}</td>
                        <td>{{ $task->status }}</td>
                        <td>
                            @can('editTask',$task)

                            <a class="btn btn-sm btn-info" href="{{ route('tasks.edit', $task) }}">
                                Edit
                            </a>
                            @endcan
                            @can('deleteTask',$task)
                            @if ($task->deleted_at==null)
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                            </form>
                            @else
                            <form action="{{ route('tasks.force-delete', $task->id) }}" method="POST" onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $tasks->withQueryString()->links() }}
        </div>
    </div>

@endsection
