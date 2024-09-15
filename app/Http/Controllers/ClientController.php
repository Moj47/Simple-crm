<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter=$request->get('deleted');
        // dd($filter);
        if($filter==null||$filter=='false')
        {
            $clients=Client::paginate(10);
            $users=User::all();
            return view('Clients.index')
            ->with('users',$users)
            ->with('clients',$clients);
        }
        if($filter=='true')
        {
            $clients=Client::onlyTrashed()->paginate(10);
            $users=User::all();
            return view('Clients.index')
            ->with('users',$users)
            ->with('clients',$clients);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $client=Client::factory()->make();
        $this->authorize('createclient',$client);
        return view('Clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client=Client::factory()->make();
        $this->authorize('createclient',$client);
        $request->validate([
            'name'=>'string|required',
            'email'=>'string|email',
            'phone' => 'required | phone_number',
            'address'=>'string|required',
        ]);
        Client::create($request->except('_token'));
        return redirect()->route('clients.index')->with('success','Client created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $this->authorize('editclient',$client);
        return view('Clients.edit')->with('client',$client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $this->authorize('editclient',$client);

        $request->validate([
            'name'=>'string|required',
            'email'=>'string|email',
            'phone' => 'required | phone_number',
            'address'=>'string|required',
        ]);
        $client->update($request->except(['_token','_method']));
        return redirect()->route('clients.index')->with('success','Client created successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->authorize('deleteclient',$client);
        $client->delete();
        return back();
    }
    public function forcedelete($id)
    {
        $client=Client::onlyTrashed()->findOrFail($id);
        $this->authorize('deleteClient', $client);
        try {
            $client->forceDelete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
               return redirect()->back()->with('status', 'Client belongs to project. Cannot delete.');
           }
        }
        return back();

    }

    public function restore($id)
    {
        $client=Client::onlyTrashed()->findOrFail($id);
        $this->authorize('restoreclient',$client);
        $client->restore();
        return back();
    }
}
