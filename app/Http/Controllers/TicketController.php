<?php

namespace App\Http\Controllers;

use App\Models\Ticketing;
use App\Models\TicketingReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use File;
use Image;

class TicketController extends Controller
{
    private $url = 'ticket';
    private $form_id = 'booking_form';
    private $table_name = 'ticketings';
    private $prefix_name = 'PAAI-TICKET-';
    private $destinationPath = '/uploads/member/ticket/';

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
        return view('pages.frontend.ticket',$data);
    }

    public function create(Request $request)
    {

        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        return view('pages.frontend.ticket_create', $data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'remarks' => 'required'

        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            DB::beginTransaction();
            $ticketing = new Ticketing;
            $ticketing->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name);
            $ticketing->user_id = Auth::user()->id;
            $ticketing->is_closed = 'Waiting';
            $ticketing->subject = $request['subject'];
            $ticketing->remarks = $request['remarks'];
            $file = $request->file('attachment');
            if ($request->hasFile('attachment')) {
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
                $ticketing->attachment = $this->destinationPath . '' . $photoName;
            }

            if($ticketing->save()){
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            }else{
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    public function show(Request $request, $id)
    {

        $data['url'] = $this->url;
        $data['method'] = 'POST';
        $data['ticketing'] = Ticketing::findOrFail($id);
        return view('pages.frontend.ticket_view', $data);
    }


    public function datatables(Request $request)
    {

        $status = $request['status'];
        $ticket = Ticketing::select(
            'ticketings.*')
        ->where('ticketings.is_closed', $status)
        ->get();

        return DataTables::of($ticket)
            ->editColumn('created_at', function ($data) {
                return date("j M Y", strtotime($data->created_at));
            })
            ->make(true);
    }

}
