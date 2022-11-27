<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $url = 'user';
    private $form_id = 'user_form';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        return view('pages.backend.administrator.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Auth::user()->type == 'member') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        return view('pages.backend.administrator.user.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->type == 'member') {
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $user = new User;
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->type = $request['type'];
            $user->bio = $request['bio'];
            if ($user->save()) {
                $admin = new Admin;
                $admin->user_id = $user->id;
                $admin->code = HomeController::getMasterCode('admin', 'EMP');
                $admin->name = $request['username'];
                $admin->email  = $request['email '];
                if (!$admin->save()) {
                    DB::rollBack();
                    $request->session()->flash('error', 'You are failed in inputing your data !!!');
                }
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['user'] = User::findOrFail($id);
        return view('pages.backend.administrator.user.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['user'] = User::findOrFail($id);
        return view('pages.backend.administrator.user.editor', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $user->type = $request['type'];
            $user->bio = $request['bio'];
            $user->updated_by = Auth::user()->username;
            if ($user->save()) {

                $request->session()->flash('success', 'You are success in updating your data');
            } else {
                $request->session()->flash('error', 'You are failed in updating your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function reset_password(Request $request)
    {
        $id = $request['user_id'];
        if (!empty($request['password'])) {
            $user = User::findOrFail($id);
            $user->password = bcrypt($request['password']);
            $user->updated_by = Auth::user()->name;
            if ($user->save()) {
                $request->session()->flash('success', 'You are success in reseting ' . $user['name'] . ' password');
            } else {
                $request->session()->flash('error', 'You are failed in reseting ' . $user['name'] . ' password !!!');
            }
        } else {
            $request->session()->flash('error', 'You have to input new password for reset it !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(Request $request)
    {
        $users = User::select(
            'users.*',
            'users.username as username',
            'users.email as email',
            'users.created_at as created_at',
            'users.updated_at as updated_at'
            )
        ->where('type','admin')
        ->get();

        return DataTables::of($users)->make(true);
    }
}
