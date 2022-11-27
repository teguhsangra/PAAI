<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm(Request $request,$token){
        $data['token'] = $token;
        return view('auth.passwords.reset',$data);
    }

    public function submitResetPasswordForm(Request $request){
        $user = User::where('email', $request->email)->first();

        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);




        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $password =  $request->password;
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user,$password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if($status === Password::PASSWORD_RESET){
                DB::table('password_resets')->where(['email'=> $request->email])->delete();
                $request->session()->flash('success', 'Password and berhasil diubah');
            }else{
                $request->session()->flash('error', 'You are failed in inputing your request !!!');
            }
        }

        return Redirect::to('login');

    }

    protected function forgotPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect('forgot-password')
                ->withErrors($validator)
                ->withInput();
        } else {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {

                $request->session()->flash('success', 'Silahkan periksa email anda untuk reset password. <br> Periksa folder spam anda apabila tidak ada di inbox');
            } else {
                $request->session()->flash('error', 'You are failed in inputing your request !!!');
            }

            return Redirect::to('forgot-password');
        }
    }
}
