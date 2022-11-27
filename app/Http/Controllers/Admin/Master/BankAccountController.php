<?php

namespace App\Http\Controllers\Admin\Master;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    private $url = 'master/bank_account';
    private $form_id = 'bank_account_form';
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
        return view('pages.backend.master.bank_account.index', $data);
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
        return view('pages.backend.master.bank_account.editor', $data);
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
            'account_no' => 'required|unique:bank_accounts',
            'account_name' => 'required',
            'bank_name' => 'required',
            'branch_code' => 'required',
            'swift_code' => 'required',
            'currency_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $bank_account = new BankAccount;
            $bank_account->account_no = $request['account_no'];
            $bank_account->account_name = $request['account_name'];
            $bank_account->bank_name = $request['bank_name'];
            $bank_account->branch_code = $request['branch_code'];
            $bank_account->swift_code = $request['swift_code'];
            $bank_account->currency_code = $request['currency_code'];
            $bank_account->desc = $request['desc'];
            $bank_account->created_by = Auth::user()->name;
            if($bank_account->save()){
                $request->session()->flash('success', 'You are success in inputing your data');
            }else{
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
        $data['bank_account'] = BankAccount::findOrFail($id);
        return view('pages.backend.master.bank_account.detail', $data);
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
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['bank_account'] = BankAccount::findOrFail($id);
        return view('pages.backend.master.bank_account.editor', $data);
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
        $bank_account = BankAccount::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'account_no' => 'required|unique:bank_accounts,account_no,'.$bank_account->id,
            'account_name' => 'required',
            'bank_name' => 'required',
            'branch_code' => 'required',
            'swift_code' => 'required',
            'currency_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $bank_account->account_no = $request['account_no'];
            $bank_account->account_name = $request['account_name'];
            $bank_account->bank_name = $request['bank_name'];
            $bank_account->branch_code = $request['branch_code'];
            $bank_account->swift_code = $request['swift_code'];
            $bank_account->currency_code = $request['currency_code'];
            $bank_account->desc = $request['desc'];
            $bank_account->updated_by = Auth::user()->name;
            if($bank_account->save()){
                $request->session()->flash('success', 'You are success in updating your data');
            }else{
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
        $bank_account = BankAccount::findOrFail($id);
        if($bank_account->delete()){
            $request->session()->flash('success', 'You are success in deleting your data');
        }else{
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(Request $request){
        $bank_accounts = BankAccount::get();

        return DataTables::of($bank_accounts)->make(true);
    }
}
