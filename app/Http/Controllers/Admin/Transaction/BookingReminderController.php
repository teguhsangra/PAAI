<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\FollowUp;
use App\Models\Location;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingReminderExport;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\ParameterSettingController;

class BookingReminderController extends Controller
{
    private $url = 'booking_reminder';
    private $ids = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->reminder_default_month = ParameterSettingController::getParameter("reminder_default_month");
    }

    public function index(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        $location_id = $request['location_id'];

        $data['location_id'] = $location_id;
        $data['room_category_id'] = $request['room_category_id'];
        $data['renewal_status'] = $request['renewal_status'];

        $data['location'] = Location::all();
        $data['room_categories'] = RoomCategory::where('code', '!=', 'LO')->get();

        return view('pages.transaction.booking_reminder.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required',
            'follow_up_date' => 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('error', "You have to fill every form coloumn to input follow up");

            return Redirect::to($this->url);
        } else {
            $bookings_id = $request['booking_id'];
            $invoice_id = $request['invoice_id'];
            if ($bookings_id != null) {
                $follow_up_number = 1;
                $booking_follow_ups = FollowUp::Where([
                    'booking_id' => $bookings_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($booking_follow_ups);
            } else {
                $follow_up_number = 1;
                $invoice_follow_ups = FollowUp::Where([
                    'invoice_id' => $invoice_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($invoice_follow_ups);
            }

            DB::beginTransaction();
            $follow_up = new FollowUp;
            $follow_up->booking_id = $request['booking_id'];
            $follow_up->invoice_id = $request['invoice_id'];
            $follow_up->follow_up_number = $follow_up_number;
            $follow_up->remarks = $request['remarks'];
            $follow_up->follow_up_date = date('Y-m-d', strtotime($request['follow_up_date']));
            $follow_up->created_by = Auth::user()->name;
            if ($follow_up->save()) {
                DB::commit();
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
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
        //
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
        $booking = Booking::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'remarks' => 'required'
        ]);
        if ($validator->fails()) {
            $request->session()->flash('error', "You have to fill every form coloumn to input follow up");

            return Redirect::to($this->url);
        } else {
            DB::beginTransaction();
            $booking->discard_or_cancel_reason = $request['remarks'];
            $booking->renewal_status = $request['renewal_status'];
            if ($booking->save()) {
                DB::commit();
                $message = '';
                if ($request['renewal_status'] == "RN") {
                    $message = "Booking " . $booking->code . " is ready to renew";
                } else {
                    $message = "Booking " . $booking->code . " is ready to terminate";
                }
                $request->session()->flash('success', $message);
            } else {
                DB::rollBack();
                $request->session()->flash('error', 'You are failed in execute your data !!!');
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
        $booking = Booking::findOrFail($id);
        $booking->status_id = 4;
        if ($booking->save()) {
            $request->session()->flash('success', "Booking " . $booking->code . " is terminated");
        } else {
            $request->session()->flash('error', 'You are failed in execute your data !!!');
        }
        return Redirect::to($this->url);
    }


    public function get_child_of_this_employee($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);

        $show_data_by_structure = false;

        if ($a_g_and_module != null) {
            if ($a_g_and_module->showDataByStructure == 1) {
                $show_data_by_structure = true;
            }
        }

        if ($show_data_by_structure) {
            $employee = Employee::findOrFail($id);
            if (sizeof($employee->this_child) > 0) {
                foreach ($employee->this_child as $no => $detail) {
                    $this->ids[sizeof($this->ids)] = $detail->id;
                    $this->get_child_of_this_employee($detail->id);
                }
            }
        } else {
            $employees = Employee::where('id', '!=', $id)->get();
            foreach ($employees as $detail) {
                array_push($this->ids, $detail->id);
            }
        }
    }

    public function datatables(Request $request)
    {
        // $reminder_end_date = date('Y-m-d', strtotime("+" . $this->reminder_default_month->int_value . " months"));
        $reminder_end_date = date('Y-m-d');

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(1, 2);

        $location_id = $request['location_id'];
        $room_category_id = $request['room_category_id'];
        $renewal_status = $request['renewal_status'];

        if ($location_id != null && $room_category_id != null && $renewal_status != null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'product')
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id != null && $room_category_id == null && $renewal_status == null) {

            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } elseif ($location_id == null && $room_category_id != null && $renewal_status == null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.type', 'product')
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id == null && $room_category_id == null && $renewal_status != null) {

            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.type', 'product')
                ->where('bookings.renewal_status', $renewal_status)
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } elseif ($location_id != null && $room_category_id != null && $renewal_status == null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'product')
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id == null && $room_category_id != null && $renewal_status != null) {
            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'product')
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id != null && $room_category_id == null && $renewal_status != null) {

            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where('bookings.renewal_status', $renewal_status)
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } else {
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        }

        return DataTables::of($bookings)
            ->editColumn('start_date', function ($data) {
                return date("j M Y", strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                return date("j M Y", strtotime($data->end_date));
            })
            ->make(true);
    }

    public function getDataBookingReminder(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $invoice_id = $request->input('invoice_id');

        if ($booking_id != null) {
            $booking_follow_ups = FollowUp::where('booking_id', $booking_id)->get();
        } else {
            $booking_follow_ups = FollowUp::where('invoice_id', $invoice_id)->get();
        }

        return $booking_follow_ups;
    }

    public function exportToExcel(Request $request)
    {
        // $reminder_end_date = date('Y-m-d', strtotime("+" . $this->reminder_default_month->int_value . " months"));
        $array_of_booking = array();
        $reminder_end_date = date('Y-m-d');

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(1, 2);

        $location_id = $request['location_id'];
        $room_category_id = $request['room_category_id'];
        $renewal_status = $request['renewal_status'];

        $booking = Booking::where('booking_id', '!=', null)->where('is_renewal', "Y")->get();
        foreach ($booking as $item) {
            array_push($array_of_booking, $item->id);
        }

        if ($location_id != null && $room_category_id != null && $renewal_status != null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'product')
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id != null && $room_category_id == null && $renewal_status == null) {

            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->whereIn('bookings.id', '!=', $array_of_booking)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } elseif ($location_id == null && $room_category_id != null && $renewal_status == null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.type', 'product')
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id == null && $room_category_id == null && $renewal_status != null) {

            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.type', 'product')
                ->where('bookings.renewal_status', $renewal_status)
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->whereIn('bookings.id', '!=', $array_of_booking)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } elseif ($location_id != null && $room_category_id != null && $renewal_status == null) {

            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'product')
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.location_id', $location_id)
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id == null && $room_category_id != null && $renewal_status != null) {
            if ($room_category_id == "VO") {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'product')
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                    ->whereIn('bookings.status_id', $active_status_id)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            } else {
                $bookings = Booking::select(
                    'bookings.*',
                    'locations.name as location_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    DB::raw(
                        '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                    ),
                    'room_categories.code as room_category'
                )
                    ->join('locations', 'bookings.location_id', 'locations.id')
                    ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                    ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                    ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                    ->where('bookings.type', 'room')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.renewal_status', $renewal_status)
                    ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                    ->whereIn('bookings.id', '!=', $array_of_booking)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('bookings.is_main_agreement', 'Y')
                    ->get();
            }
        } elseif ($location_id != null && $room_category_id == null && $renewal_status != null) {
            dd('test');
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                CASE WHEN bookings.type = "package" THEN "Package"
                WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where('bookings.renewal_status', $renewal_status)
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->whereIn('bookings.id', '!=', $array_of_booking)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        } else {
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'customers.email as customer_email',
                DB::raw(
                    '(
                    CASE WHEN bookings.type = "package" THEN "Package"
                    WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                ),
                'room_categories.code as room_category'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)

                ->whereIn('bookings.status_id', $active_status_id)
                ->whereIn('bookings.id', '!=', $array_of_booking)
                ->where('bookings.is_main_agreement', 'Y')
                ->get();
        }

        $data['bookings'] = $bookings;

        return Excel::download(new BookingReminderExport($data), 'booking_reminder_' . $renewal_status . '_' . date('Y_m_d_H_i_s') . '.xlsx');
    }
}
