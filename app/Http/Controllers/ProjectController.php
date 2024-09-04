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
    public function index()
    {
        $porjects=Project::paginate(20);
        return view('projects.index')->with('projects',$porjects);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'deadline'=>'required|date',
            'user_id'=>'required|integer|exists:users,id',
            'client_id'=>'required|integer|exists:clients,id',
        ]);

        $project->update($request->except(['_token','_method']));
        return to_route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
