<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;


class SubscriptionController extends Controller
{
    private $url = 'subscription';

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
        return view('pages.frontend.subscription',$data);
    }

    public function datatables(Request $request)
    {

        $booking = Booking::where('member_id', Auth::user()->member->id)
        ->get();


        return DataTables::of($booking)
        ->editColumn('start_date', function ($data) {

            return date("j F Y",strtotime($data->start_date));
        })
        ->editColumn('end_date', function ($data) {

            return date("j F Y",strtotime($data->end_date));
        })
        ->editColumn('total', function ($data) {
            $total_price = $data->total;
            return number_format($total_price, 0, ',', '.');
        })
        ->make(true);
    }
}
