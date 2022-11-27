<?php

namespace App\Http\Controllers\Admin\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Room;
use App\Models\Product;
use App\Models\Package;
use App\Models\Ticketing;
use App\Models\TicketingSubject;
use App\Models\TicketingReply;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketingController extends Controller
{
    private $url = 'ticketing';
    private $form_id = 'ticketing_form';
    private $ids = array();
    private $table_name = 'ticketings';
    private $prefix_name = 'PAAI-TICKET-';

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
        return view('pages.backend.transaction.ticket.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if (Auth::user()->type == 'member') {
        //     $request->session()->flash('error', 'You are not allowed to access this module !!!');
        //     return Redirect::to('/');
        // }

        // $data['url'] = $this->url;
        // $data['form_url'] = $this->url;
        // $data['form_id'] = $this->form_id;
        // $data['method'] = 'POST';
        // $data['button_name'] = 'Create';
        // $data['locations'] = Auth::user()->location;
        // $data['customers'] = Customer::get();
        // $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        // $data['room'] = Room::get();
        // $data['products'] = Product::where('main_status', 'Y')->get();
        // $data['package'] = Package::get();
        // $data['subjects'] = TicketingSubject::get();

        // return view('pages.transaction.ticketing.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $a_g_and_module = HomeController::getAccess($this->url);
        // $employee = Employee::where('user_id', Auth::user()->id)->first();
        // if ($a_g_and_module == null || $employee == null) {
        //     $request->session()->flash('error', 'You are not allowed to access this module !!!');
        //     return Redirect::to('profile');
        // }
        // $validator = Validator::make($request->all(), [
        //     'location_id' => 'required',
        //     'customer_id' => 'required',
        //     'contact_id' => 'required',
        //     'employee_id' => 'required',

        // ]);

        // if ($validator->fails()) {
        //     return redirect($this->url . '/create')
        //         ->withErrors($validator)
        //         ->withInput();
        // }else{

        //     DB::beginTransaction();
        //     $ticketing = new Ticketing;
        //     $ticketing->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
        //     $ticketing->user_id = Auth::user()->id;
        //     $ticketing->location_id = $request['location_id'];
        //     $ticketing->customer_id =  $request['customer_id'];
        //     $ticketing->contact_id = $request['contact_id'];
        //     $ticketing->room_id = $request['room_id'];
        //     $ticketing->product_id = $request['product_id'];
        //     $ticketing->package_id = $request['package_id'];
        //     $ticketing->booking_id = $request['booking_id'];
        //     $ticketing->order_id = $request['order_id'];
        //     $ticketing->ticketing_subject_id = $request['ticketing_subject_id'];
        //     $ticketing->is_closed = 'N';
        //     $ticketing->subject = $request['subject'];
        //     $ticketing->remarks = $request['remarks'];

        //     if($ticketing->save()){
        //         DB::commit();
        //         $request->session()->flash('success', 'You are success in inputing your data');
        //     }else{
        //         DB::rollBack();
        //         $request->session()->flash('error', 'You are failed in inputing your data !!!');
        //     }
        //     return Redirect::to($this->url);
        // }
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
        $data['url_reply'] = "ticketing/reply";
        $data['method'] = 'POST';
        $data['ticketing'] = Ticketing::findOrFail($id);
        $data['id'] = $id;
        return view('pages.backend.transaction.ticket.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $ticketing = Ticketing::findOrFail($id);
        $ticketing->is_closed = "Solved";
        if ($ticketing->save()) {
            $request->session()->flash('success', 'Closed Ticketing = ' . $ticketing->code);
        } else {
            $request->session()->flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }



    public function datatables(Request $request)
    {
        $ticketing = Ticketing::select('ticketings.*','users.username as user'
        )->join('users','users.id','=','ticketings.user_id')
        ->get();
        return DataTables::of($ticketing)
        ->make(true);
    }


    public function ticketing_reply(Request $request)
    {
        $ticketing_id = $request['ticketing_id'];
        $ticketing = Ticketing::findOrFail($ticketing_id);


        $validator = Validator::make($request->all(), [
            'remarks' => 'required',

        ]);

        if ($validator->fails()) {
             return redirect($this->url . '/' . $ticketing_id)
                ->withErrors($validator)
                ->withInput();
        }else{

            DB::beginTransaction();
            $ticketing->is_closed = "On Going";
            if($ticketing->save()){
                $reply = new TicketingReply;
                $reply->user_id = $request['user_id'];
                $reply->ticketing_id = $request['ticketing_id'];
                $reply->remarks = $request['remarks'];
                if(!$reply->save())
                {
                    DB::rollBack();
                    $request->session()->flash('error', 'You are failed in inputing your data !!!');
                }
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            }else{
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url . '/' . $ticketing_id);
        }
    }
}
