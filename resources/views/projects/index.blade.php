@extends('layouts.app')

@section('content')
@can('create-project')
    <div style="margin-bottom: 10px;" class="row">

        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('projects.create') }}">
                Create project
            </a>
        </div>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">Projects list</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <form action="{{ route('projects.index') }}" method="GET">
                    <div class="form-group row">
                        <label for="status" class="col-form-label">Status:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>deleted</option>
                                {{-- @foreach(App\Models\Project::STATUS as $status)
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
                    <th>Title</th>
                    <th>Assigned to</th>
                    <th>Client</th>
                    <th>Deadline</th>
                    <th>Status</th>

                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></td>
                        <td>{{ optional($project->user)->name }}</td>
                        <td>{{ optional($project->client)->name }}</td>
                        <td>{{ $project->deadline }}</td>
                        <td>{{ $project->status }}</td>
                        <td>
                            @can('update-project',$project)

                            <a class="btn btn-sm btn-info" href="{{ route('projects.edit', $project) }}">
                                Edit
                            </a>
                            @endcan
                            @can('force-delete-project',$project)
                                <form
                                @if ($project->deleted_at==null)
                                action="{{ route('projects.destroy', $project) }}" method="POST"
                                @else
                                action="{{ route('projects.force-delete', $project->id) }}" method="POST"

                                @endif

                                      onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                </form>
                            @endcan
                                @can('restore-project',$project)

                                @if (!$project->deleted_at==null)
                                <form action="{{ route('projects.restore', $project->id) }}" method="POST"
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

            {{ $projects->withQueryString()->links() }}
        </div>
    </div>

@endsection
