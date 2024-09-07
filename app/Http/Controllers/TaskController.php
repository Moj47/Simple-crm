<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks=Task::paginate(10);
        return view('tasks.index')
        ->with('tasks',$tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users=User::all();
        $clients=Client::all();
        $projects=Project::all();
        return view('tasks.create')
        ->with('clients',$clients)
        ->with('users',$users)
        ->with('projects',$projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'name'=>'required|string',
        'description'=>'required|string',
        'deadline'=>'required|date',
        'user_id'=>'required|integer|exists:users,id',
        'client_id'=>'required|integer|exists:clients,id',
        'project_id'=>'required|integer|exists:projects,id',
        'status' => 'required|in:1,2',
    ]);
    $task=Task::create($request->except('_token'));
    return to_route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show')
        ->with('task',$task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $users=User::all();
        $clients=Client::all();
        $projects=Project::all();
        return view('tasks.edit')
        ->with('task',$task)
        ->with('clients',$clients)
        ->with('users',$users)
        ->with('projects',$projects);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'deadline'=>'required|date',
            'user_id'=>'required|integer|exists:users,id',
            'client_id'=>'required|integer|exists:clients,id',
            'project_id'=>'required|integer|exists:projects,id',
            'status' => 'required|in:1,2',
        ]);
        $task->update($request->except(['_token','_method']));
        return to_route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
