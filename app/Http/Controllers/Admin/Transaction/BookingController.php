<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Models\Status;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Member;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\Master\MemberController;
use File;
use Image;
use Mail;

class BookingController extends Controller
{
    private $url = 'bookings';
    private $form_id = 'booking_form';
    private $table_name = 'bookings';
    private $prefix_name = 'PAAI-MB-';
    private $destinationPath = '/uploads/member/booking/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }

        $data['url'] = $this->url;
        $data['statuses'] = Status::all();
        return view('pages.backend.transaction.booking.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $is_renewal = "N";
        if (!empty($request['booking_id'])) {
            $booking = Booking::findOrFail($request['booking_id']);
            $data['booking'] = $booking;
            $data['booking_id'] = $request['booking_id'];
            $is_renewal = "Y";
        }
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['members'] = Member::get();
        $data['is_renewal'] = $is_renewal;
        return view('pages.backend.transaction.booking.editor', $data);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    public function store(Request $request)
    {

        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'attachment' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $member_id = null;

            $member_id = MemberController::create_from_transaction($request);
            if ($member_id == null) {
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            $status = Status::where('name', $request['status_name'])->first();
            $booking = new Booking;
            $booking->booking_id = $request['booking_id'];
            $booking->status_id = $status->id;
            $booking->member_id   = $member_id;;
            $booking->code = self::createCustomCode($this->table_name, $this->prefix_name);
            $booking->product_id = $request['product_id'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->total = $request['total'];
            $booking->is_renewal = $request['is_renewal'];
            $booking->term_notice_period = $request['term_notice_period'];
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
                $booking->attachment = $this->destinationPath . '' . $photoName;
            }

            switch ($status->action) {
                case "draft":
                    $booking->payment_status = "not paid";
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->payment_status = "paid";
                    $booking->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $booking->payment_status = "paid";
                    $booking->complete_by = Auth::user()->name;
                    break;
            }
            if ($booking->save()) {
                if ($status->action == "posting") {
                    $data['booking'] = $booking;
                    $data['url'] = $this->url;
                    Mail::send('mail.notification', $data, function ($message) use ($data) {
                        $message->to($data['booking']->member->email)
                            ->cc(['paai.or.id@gmail.com', 'hennydondocambey@gmail.com'])
                            ->subject('Notification Membership ');
                    });

                    if (Mail::failures()) {
                        $request->session()->flash('error', 'Email Sent Failed');
                    } else {
                        $request->session()->flash('success', 'Email Sent');
                    }
                }
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }

        $data['url'] = $this->url;

        $data['booking'] = Booking::findOrFail($id);
        return view('pages.backend.transaction.booking.detail', $data);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
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

        $data['members'] = Member::get();

        $booking = Booking::findOrFail($id);

        $data['booking'] = $booking;
        return view('pages.backend.transaction.booking.editor', $data);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $booking = Booking::findOrFail($id);
            $status = Status::where('name', $request['status_name'])->first();

            $member_id = MemberController::create_from_transaction($request);
            if ($member_id == null) {
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            $booking->status_id = $status->id;
            $booking->member_id   = $member_id;
            $booking->product_id = $request['product_id'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->total = $request['total'];
            $booking->term_notice_period = $request['term_notice_period'];
            $booking->is_renewal = $request['is_renewal'];
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

                if (!empty($booking->attachment)) {
                    File::Delete(public_path($booking->attachment));
                }

                $booking->attachment = $this->destinationPath . '' . $photoName;
            }
            switch ($status->action) {
                case "draft":
                    $booking->payment_status = "not paid";
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->payment_status = "paid";
                    $booking->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $booking->payment_status = "paid";
                    $booking->complete_by = Auth::user()->name;
                    break;
            }
            if ($booking->save()) {

                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }

        $discard_or_cancel_reason = $request['discard_or_cancel_reason'];
        $booking = Booking::findOrFail($id);

        $status = $booking->status;
        if ($booking->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($booking->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }
        $booking->status_id = $status->id;
        $booking->discard_or_cancel_reason = $discard_or_cancel_reason;
        switch ($status->action) {
            case "discard":
                $booking->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $booking->cancel_by = Auth::user()->name;
                break;
        }
        if ($booking->save()) {
            $request->session()->flash('success', 'Booking = ' . $booking->code);
        } else {
            $request->session()->flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }


    // public function print(Request $request, $id)
    // {
    //     $data['booking'] = Booking::findOrFail($id);
    //     $data['complimentaries'] = Complimentary::get();
    //     $data['company_name'] = $this->company_name->string_value;
    //     $data['company_address_1'] = $this->company_address_1->string_value;
    //     $data['company_address_2'] = $this->company_address_2->string_value;
    //     $data['company_phone'] = $this->company_phone->string_value;
    //     $data['company_fax'] = $this->company_fax->string_value;
    //     $data['tax_percentage'] = $this->tax_percentage;
    //     $data['service_charge'] = $this->service_charge;
    //     $data['director_name'] = $this->director_name->string_value;
    //     if (!empty($request['mobile_status'])) {
    //         return view('pages.transaction.serviced_office.mobile', $data);
    //     } else {
    //         return view('pages.transaction.serviced_office.print', $data);
    //     }
    // }

    public function datatables(Request $request)
    {

        $booking = DB::table('bookings')
            ->join('members', 'members.id', 'bookings.member_id')
            ->join('products', 'products.id', 'bookings.product_id')
            ->select('bookings.*', 'members.name as member_name', 'products.name as product_name')
            ->where('bookings.status_id', $request['status_id'])
            ->get();

        return DataTables::of($booking)
            ->editColumn('total', function ($data) {
                $total_price = $data->total;
                return number_format($total_price, 0, ',', '.');
            })
            ->editColumn('start_date', function ($data) {

                return date("j F Y", strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {

                return date("j F Y", strtotime($data->end_date));
            })
            ->make(true);
    }
}
