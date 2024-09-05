<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::paginate(10);
        return view('users.index')
        ->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('Users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'address'=>'required|string',
            'phone_number'=>'required|phone_number',
        ]);
        $user=array_merge(['password'=>'password'],$request->except(['_token']));
        User::create($user);
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
    public function edit(User $user)
    {
        return view('Users.edit')
        ->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'address'=>'required|string',
            'phone_number'=>'required|phone_number',
        ]);
        $data=array_merge(['password'=>'password'],$request->except(['_token','_method']));
        $user->update($data);
        return to_route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
