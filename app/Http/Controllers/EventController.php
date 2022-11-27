<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use File;
use Image;

class EventController extends Controller
{
    private $url = 'events';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        $data['url'] = $this->url;
        $member = Event::orderBy('id', 'desc')
            ->paginate(20);
        $data['event'] = $member;
        return view('pages.frontend.event', $data);
    }




    public function show(Request $request, $slug)
    {

        $data['url'] = $this->url;
        $data['method'] = 'POST';
        $data['event'] = Event::where('slug', $slug)->first();
        return view('pages.frontend.event_detail', $data);
    }
}
