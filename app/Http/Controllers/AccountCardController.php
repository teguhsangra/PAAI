<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Member;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountCardController extends Controller
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
        $data['member'] = Member::where('user_id',Auth::user()->id)->first();
        $data['merchant'] = Merchant::get();
        return view('pages.frontend.card',$data);
    }


}
