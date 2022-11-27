<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use File;
use Image;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    private $url = 'account';
    private $form_id = 'member_form';
    private $table_name = 'members';
    private $destinationPath = '/uploads/member/user/';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request){
        $data['url'] = $this->url;
        $data['member'] = Member::where('user_id',Auth::user()->id)->first();
        $data['form_url'] = $this->url . '/' . $data['member']->id;

        return view('pages.frontend.profile',$data);
    }

    public function update(Request $request, $id)
    {

        $profile = Member::find($id);

        $user = $profile->user;

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect($this->url)
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $profile->aaji = $request['aaji'];
            $profile->name = $request['name'];
            $profile->email = $request['email'];
            $profile->phone = $request['phone'];
            $profile->company_name = $request['company_name'];
            $profile->birth_date = date('Y-m-d', strtotime($request['birth_date']));
            $profile->url_facebook = $request['url_facebook'];
            $profile->url_instagram = $request['url_instagram'];
            $profile->url_twitter = $request['url_twitter'];
            $profile->url_youtube = $request['url_youtube'];
            $file = $request->file('picture');
            if ($request->hasFile('picture')) {
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

                $profile->picture =  $this->destinationPath . '' . $photoName;
            }
            if ($profile->save()) {
                $user->username = $request['name'];
                $user->email = $request['email'];
                if (!empty($request['password'])) {
                    $user->password = Hash::make($request['password']);
                }
                if(!$user->save())
                {
                    DB::rollBack();
                    $request->session()->flash('error', 'You are failed in updating your data !!!');
                }
                DB::commit();
                $request->session()->flash('success', 'You are success in updating your data');
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in updating your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

}
