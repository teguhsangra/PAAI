<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    private $url = 'admin/profile';
    private $destinationPath = '/uploads/profile/';
    protected $main_path;
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
        $profile = Admin::where('user_id', Auth::user()->id)->first();



        $data['form_url'] = $this->url . '/' . $profile->id;
        $data['profile'] = $profile;
        return view('pages.backend.profile.index', $data);
    }

    public function update(Request $request, $id)
    {

        $profile = Admin::find($id);



        $user = $profile->user;

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'username' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect($this->url)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $profile->name = $request['username'];
            $profile->email = $request['email'];

            if ($profile->save()) {
                $user->username = $request['username'];
                $user->email = $request['email'];
                if (!empty($request['password'])) {
                    $user->password = Hash::make($request['password']);
                }

                $file = $request->file('photo');
                if ($request->hasFile('photo')) {
                    $photoName = time() . '.' . $file->getClientOriginalExtension();

                    $path = public_path($this->destinationPath);
                    HomeController::check_exist_folder($path);
                    $path = $path . $photoName;

                    if ($file->getSize() > 1000000) {
                        Image::make($file->getRealPath())->resize(1024, 1024, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($path);
                    } else {
                        Image::make($file->getRealPath())->save($path);
                    }


                    if ($user->photo != null) {
                        File::Delete(public_path($photoName));
                    }

                    $user->picture =  $this->destinationPath . '' . $photoName;
                }
                $user->save();
                DB::commit();
                $request->session()->flash('success', 'You are success in updating your data');
            } else {
                DB::rollBack();
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
        //
    }
}
