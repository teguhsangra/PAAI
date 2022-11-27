<?php

namespace App\Http\Controllers\Admin\Master;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

class MerchantController extends Controller
{
    private $url = 'master/merchant';
    private $form_id = 'merchant_form';
    private $destinationPath = '/uploads/merchant/';
    protected $main_path;
    private $table_name = 'sponsorships';
    private $prefix_name = 'SPO';
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
        return view('pages.backend.master.merchant.index', $data);
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
        return view('pages.backend.master.merchant.editor', $data);
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
            'name' => 'required|unique:merchants',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $merchant = new Merchant;
            $merchant->name = $request['name'];
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
                $merchant->picture = $this->destinationPath . '' . $photoName;
            }
            $merchant->desc = $request['desc'];
            $merchant->created_by = Auth::user()->username;
            if ($merchant->save()) {
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        $data['merchant'] = Merchant::findOrFail($id);
        return view('pages.backend.master.merchant.detail', $data);
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
        $data['merchant'] = Merchant::findOrFail($id);
        return view('pages.backend.master.merchant.editor', $data);
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
        $merchant = Merchant::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:merchants,name,' . $merchant->id
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $merchant->name = $request['name'];
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

                if (!empty($merchant->picture)) {
                    File::Delete(public_path($merchant->picture));
                }

                $merchant->picture = $this->destinationPath . '' . $photoName;
            }
            $merchant->desc = $request['desc'];
            $merchant->updated_by = Auth::user()->name;
            if ($merchant->save()) {
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
        $merchant = Merchant::findOrFail($id);
        if ($merchant->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }



    public function datatables(Request $request)
    {
        $merchant = Merchant::get();

        return DataTables::of($merchant)
        ->editColumn('status', function ($data) {
            $hall = 'No';
            if($data->is_active === 'Y'){
                $hall = "yes";
            }
            return $hall;
        })
        ->make(true);
    }


}
