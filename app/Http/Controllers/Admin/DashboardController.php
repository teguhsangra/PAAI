<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    private $url = 'admin/dashboard';
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
        $expired_date = date('Y-m-d');
        $data['member'] = DB::table('members')->count();
        $data['member_active'] = DB::table('members')
        ->where('members.expired_date', '>=',$expired_date)
        ->count();
        $data['member_not_active'] = DB::table('members')
        ->where('members.expired_date', '<=',$expired_date)
        ->count();
        return view('pages.backend.dashboard.index', $data);
    }
}
