<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter=$request->get(key:'deleted');
        if( $filter==null ||$filter=='false')
        {
            $users=User::paginate(10);

        }
        if( $filter=='true')
        {
            $users=User::onlyTrashed()->paginate(10);
        }
        return view('users.index')
        ->with('users',$users);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('createUser',auth()->user());
        return view('Users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('createuser',auth()->user());

        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'address'=>'required|string',
            'phone_number'=>'required|phone_number',
        ]);
        $user=array_merge(['password'=>'password'],$request->except(['_token']));
        User::create($user);
        return to_route('users.index');
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
        if(auth()->user()->id==$user->id ||$this->authorize('edituser',auth()->user()) );
        {
            return view('Users.edit')
            ->with('user',$user);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        if(auth()->user()->id==$user->id ||$this->authorize('edituser',auth()->user()) );
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('deleteuser',auth()->user());
        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
               return redirect()->back()->with('status', 'User belongs to task. Cannot delete.');
           }
        }
        return redirect()->route('users.index');

    }
    public function forceDelete($id)
    {
        $user=User::onlyTrashed()->find($id);
        $this->authorize('deleteuser',auth()->user());
        try {
            $user->forceDelete();
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() === '23000') {
               return redirect()->back()->with('status', 'User belongs to task. Cannot delete.');
           }
        }
        return redirect()->route('users.index');

    }
    public function restore($id)
    {
        $this->authorize('restoreUser',auth()->user());
        $user=User::onlyTrashed()->find($id);
        $user->restore();
        return back();
    }
}
