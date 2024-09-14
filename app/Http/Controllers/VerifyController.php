<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{


    public function send()
    {
        return view('verify.send');
    }
    public function verify(Request $request)
    {

        $email = auth()->user()->email;

        Mail::send('verify.verify', ['email' => $email], function ($message) use ($email) {
        $message->from('hello@example.com', 'Your App Name');
        $message->to($email);
        $message->subject('Verify Your Email');
    });
    return view('verify.wait');
    }
    public function click($email)
    {

        if(auth()->check() && $email==auth()->user()->email)
        {
            $user=User::where('email',$email)->firstOrFail();
            $user->markEmailAsVerified();
        }
        return redirect()->route('projects.index');

    }
}
