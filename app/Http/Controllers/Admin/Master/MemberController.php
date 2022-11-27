<?php

namespace App\Http\Controllers\Admin\Master;

use App\Models\Status;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Exports\MemberExport;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Image;

class MemberController extends Controller
{
    private $url = 'master/member';
    private $form_id = 'member_form';
    private $table_name = 'members';
    private $prefix_name = 'ID#';
    private $destinationPath = '/uploads/members/users/';
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
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        return view('pages.backend.master.member.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        return view('pages.backend.master.member.editor', $data);
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'aaji' => ['required'],
            'company_name' => ['required'],
            'phone' => ['required'],
            'birth_date' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $birth_date = $request->input('birth_date');

            if (!empty($birth_date)) {
                $password = str_replace('-', '', $birth_date);
            } else {
                $password = 'PAAI1234';
            }

            $user = new User;
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->password = Hash::make($password);
            $user->type = 'member';
            if ($user->save()) {

                $member = new Member;
                $member->user_id = $user->id;
                $member->code  = self::createCustomCode('members', 'ID#');
                $member->aaji = $request['aaji'];
                $member->name = $user->username;
                $member->company_name = $request['company_name'];
                $member->email  = $user->email;
                $member->phone  = $request['phone'];
                $member->birth_date  = date('Y-m-d', strtotime($request['birth_date']));
                $member->address  = $request['address'];
                $member->is_verified = 'Y';
                $member->referral = $request['referral'];
                $file = $request->file('photo');
                if ($request->hasFile('photo')) {
                    $photoName = time() . '.' . $file->getClientOriginalExtension();

                    $path = public_path($this->destinationPath);
                    HomeController::check_exist_folder($path);
                    $path = $path . $photoName;

                    if ($file->getSize() > 2000000) {
                        Image::make($file->getRealPath())->resize(2024, 2024, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($path);
                    } else {
                        Image::make($file->getRealPath())->save($path);
                    }
                    $member->picture = $this->destinationPath . '' . $photoName;
                }
                if (!$member->save()) {
                    DB::rollBack();
                    $request->session()->flash('error', 'You are failed in inputing your data !!!');
                }
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your request !!!');
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        $data['member'] = Member::findOrFail($id);
        return view('pages.backend.master.member.detail', $data);
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['member'] = Member::findOrFail($id);
        return view('pages.backend.master.member.editor', $data);
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
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $member = Member::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'aaji' => ['required'],
            'company_name' => ['required'],
            'phone' => ['required'],
            'birth_date' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $member->aaji = $request['aaji'];
            $member->name = $request['username'];
            $member->company_name = $request['company_name'];
            $member->email  = $request['email'];
            $member->phone  = $request['phone'];
            $member->birth_date  = date('Y-m-d', strtotime($request['birth_date']));
            $member->address  = $request['address'];
            $member->updated_by = Auth::user()->name;
            $file = $request->file('photo');
            if ($request->hasFile('photo')) {
                $photoName = time() . '.' . $file->getClientOriginalExtension();

                $path = public_path($this->destinationPath);

                HomeController::check_exist_folder($path);
                $path = $path . $photoName;

                if ($file->getSize() > 2000000) {
                    Image::make($file->getRealPath())->resize(2024, 2024, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path);
                } else {
                    Image::make($file->getRealPath())->save($path);
                }

                if (!empty($member->picture)) {
                    File::Delete(public_path($member->picture));
                }

                $member->picture = $this->destinationPath . '' . $photoName;
            }
            if ($member->save()) {
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
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $member = Member::findOrFail($id);
        if ($member->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public  function reset_password(Request $request)
    {
        $id = $request['user_id'];
        $user = User::findOrFail($id);
        $birth_date = $user->member->birth_date;

        if (!empty($birth_date)) {
            $password = str_replace('-', '', $birth_date);
        } else {
            $password = 'PAAI1234';
        }
        $user->password = bcrypt($password);
        if ($user->save()) {
            $request->session()->flash('success', 'You are success in reseting ' . $user['name'] . ' password');
        } else {
            $request->session()->flash('error', 'You are failed in reseting ' . $user['name'] . ' password !!!');
        }
        return Redirect::to($this->url);
    }


    public function datatables(Request $request)
    {
        $role = $request['role'];
        $expired_date = date('Y-m-d');

        if ($role == 'active') {
            $member = Member::where('expired_date', '>=', $expired_date)
                ->get();
        } else if ($role == 'not_active') {
            $member = Member::where('expired_date', '<=', $expired_date)
                ->get();
        } else {
            $member = Member::get();
        }


        return DataTables::of($member)
            ->editColumn('is_verified', function ($data) {
                $verified = 'Not verified';
                if ($data->is_verified == 'Y') {
                    $verified = 'verified';
                }
                return $verified;
            })
            ->make(true);
    }

    public static function create_from_transaction($request)
    {
        $birth_date = $request->input('birth_date');
        $status = Status::where('name', $request['status_name'])->first();
        if (!empty($birth_date)) {
            $password = str_replace('-', '', $birth_date);
        } else {
            $password = 'PAAI1234';
        }

        if ($request['customer_status'] == 'N') {
            $user = new User;
            $user->username = $request['member_name'];
            $user->email = $request['member_email'];
            $user->password = Hash::make($password);
            $user->type = 'member';
            if ($user->save()) {
                $member = new Member;
                $member->user_id = $user->id;
                $member->code  = self::createCustomCode('members', 'ID#');
                $member->aaji = $request['member_aaji'];
                $member->name = $user->username;
                $member->company_name = $request['member_company_name'];
                $member->email  = $user->email;
                $member->phone  = $request['member_phone'];
                $member->birth_date  = date('Y-m-d', strtotime($request['member_birth_date']));
                switch ($status->action) {
                    case "posting":
                        $member->is_verified = 'Y';
                        $member->expired_date  = date('Y-m-d', strtotime($request['end_date']));
                        break;
                    case "complete":
                        $member->is_verified = 'Y';
                        $member->expired_date  = date('Y-m-d', strtotime($request['end_date']));
                        break;
                }


                if ($member->save()) {
                    $member_id = $member->id;
                } else {
                    $member_id = null;
                }
            }
        } else {
            $member_id = null;
            $id = $request['member_id'];
            $member = Member::findOrFail($id);
            switch ($status->action) {
                case "posting":
                    $member->is_verified = 'Y';
                    $member->expired_date  = date('Y-m-d', strtotime($request['end_date']));
                    break;
                case "complete":
                    $member->is_verified = 'Y';
                    $member->expired_date  = date('Y-m-d', strtotime($request['end_date']));
                    break;
            }
            if ($member->save()) {
                $member_id = $member->id;
            } else {
                $member_id = $id;
            }
        }

        return $member_id;
    }


    public  function exportToExcel(Request $request)
    {
        $member_status = $request['member_status'];
        $expired_date = date('Y-m-d');

        if ($member_status != null) {
            if ($member_status == "active") {
                $member = DB::table('members')
                    ->select('members.*')
                    ->where('members.expired_date', '>=', $expired_date)
                    ->get();
            } else {
                $member = DB::table('members')
                    ->select('members.*')
                    ->where('members.expired_date', '<=', $expired_date)
                    ->get();
            }
        } else {
            $member =  DB::table('members')
                ->select('members.*')
                ->get();
        }

        $data['member'] = $member;

        return Excel::download(new MemberExport($data), 'booking_reminder_' . $member_status . '_' . date('Y_m_d_H_i_s') . '.xlsx');
    }
}
