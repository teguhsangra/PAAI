<?php

namespace App\Http\Controllers;


use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Http\Controllers\BookingController;

class AuthController extends Controller
{
    private $destinationPath = '/uploads/members/payment/';

    public function index(Request $request)
    {

        return view('auth.register');
    }

    protected function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(6),
            ],
            'phone' => ['required'],
            'birth_date' => ['required'],
            'attachment' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $user = new User;
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->type = 'member';
            if ($user->save()) {
                $file = $request->file('attachment');
                if (BookingController::create_member($user, $request->all(), $file)) {
                    // Do Nothing
                } else {
                    DB::rollBack();
                    $request->session()->flash('error', 'You are failed in inputing your data !!!');
                }

                $user->sendApiEmailVerificationNotification();

                DB::commit();

                $request->session()->flash('success', 'Silahkan periksa email anda untuk verifikasi. <br> <p style="font-size: 12px;color:black"><span style="color: red">*</span>) anda tidak akan bisa login sebelum melakukan verifikasi akun anda yang kami kirimkan ke email anda</p>');
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your request !!!');
            }

            return Redirect::to('login');
        }
    }
}
