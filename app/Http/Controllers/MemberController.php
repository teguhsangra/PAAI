<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberController extends Controller
{

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
        $expired_date = date('Y-m-d');
        $keyword = $request['keyword'];
        $member = null;

        $member = Member::where('members.expired_date', '>=',$expired_date)
        ->orderBy('id','desc')
        ->paginate(20);

        if($keyword)
        {
            $member = Member::where('members.expired_date', '>=',$expired_date)
            ->where('name','like', '%' . $keyword . '%')
            ->orderBy('id','desc')
            ->paginate(20);
        }


        $data['member'] = $member;
        $data['keyword'] = $keyword;
        return view('pages.frontend.member',$data);
    }


}
