<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $clients=Client::paginate(10);
        $users=User::all();
        return view('Clients.index')
        ->with('users',$users)
        ->with('clients',$clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
