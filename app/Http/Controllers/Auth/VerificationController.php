<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/login';



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }

    public function verify(Request $request) {
        $userID = $request->route('id');

        $user = User::findOrFail($userID);
        $date = date('Y-m-d g:i:s');
        $user->email_verified_at = $date;

        if ($user->save()) {
            $request->session()->flash('success', 'User anda sudah aktif, Silahkan login');
        } else {
            $user->session()->flash('error', 'Your user failed been activated !!!');
        }
        return redirect('login');
    }


    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
