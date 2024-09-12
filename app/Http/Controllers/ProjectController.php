<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status');
        if($filter=='all' || $filter==null)
        {
            $porjects=Project::paginate(20);
            return view('projects.index')->with('projects',$porjects);
        }
        elseif($filter=='deleted')
        {
            $porjects=Project::onlyTrashed()->paginate(20);
            return view('projects.index')->with('projects',$porjects);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create-project');
        $users=User::all();
        $clients=Client::all();
        return view('projects.create')
        ->with('users',$users)
        ->with('clients',$clients)
        ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create-project');
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'deadline'=>'required|date',
            'user_id'=>'required|integer|exists:users,id',
            'client_id'=>'required|integer|exists:clients,id',
        ]);

        Project::Create($request->except(['_token','_method']));
        return to_route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show')->with('project',$project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update-project',$project);
        $users=User::all();
        $cleints=Client::all();
        return view('projects.edit')
        ->with('project',$project)
        ->with('clients',$cleints)
        ->with('users',$users);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update-project',$project);
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'deadline'=>'required|date',
            'user_id'=>'required|integer|exists:users,id',
            'client_id'=>'required|integer|exists:clients,id',
        ]);

        $project->update($request->except(['_token','_method']));
        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete-project',$project);
        try {
            $project->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
               return redirect()->back()->with('status', 'Project belongs to task. Cannot delete.');
           }
        }

        return redirect()->route('projects.index');
    }
    public function restore($id)
    {
        $project=Project::onlyTrashed()->find($id);
        $this->authorize('restore-project',$project);
        $project=Project::onlyTrashed()->find($id);
        $project->restore();
        return back();
    }

    public function forcedelete($id)
    {
        $project=Project::onlyTrashed()->findOrFail($id);
        $this->authorize('force-delete-project', $project);
        try {
            $project->forceDelete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
               return redirect()->back()->with('status', 'Project belongs to task. Cannot delete.');
           }
        }
        return back();

    }
}
