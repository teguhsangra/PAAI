<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use File;
use Image;

class EventController extends Controller
{
    private $url = 'event';
    private $form_id = 'event_form';
    private $destinationPath = '/uploads/event/';
    protected $main_path;
    private $table_name = 'events';
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
        return view('pages.backend.event.index', $data);
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
        return view('pages.backend.event.editor', $data);
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
        $type = $request['type'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:events',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {


            $event = new Event();
            $event->name = $request['name'];
            $event->slug =
                \Str::slug($request['name']);
            $event->start_time = $request['start_time'];
            $event->end_time = $request['end_time'];
            $event->date = date('Y-m-d', strtotime($request['date']));
            $event->desc = $request['desc'];
            $event->link = $request['link'];
            $file = $request->file('image');
            if ($request->hasFile('image')) {
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
                $event->image = $this->destinationPath . '' . $photoName;
            }



            if ($event->save()) {
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
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
        $data['event'] = Event::findOrFail($id);
        return view('pages.backend.event.detail', $data);
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
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['event'] = Event::findOrFail($id);
        return view('pages.backend.event.editor', $data);
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
        $type = $request['type'];
        $event = Event::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:events,name,' . $event->id,
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {


            $event->name = $request['name'];
            $event->slug =
                \Str::slug($request['name']);
            $event->start_time = $request['start_time'];
            $event->end_time = $request['end_time'];
            $event->date = date('Y-m-d', strtotime($request['date']));
            $event->desc = $request['desc'];
            $event->link = $request['link'];
            $file = $request->file('image');

            if ($request->hasFile('image')) {
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

                if (!empty($event->image)) {
                    File::Delete(public_path($event->image));
                }

                $event->image = $this->destinationPath . '' . $photoName;
            }






            if ($event->save()) {
                $request->session()->flash('success', 'You are success in updating your data');
            } else {
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
        $event = Event::findOrFail($id);
        if ($event->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->type == 'member') {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('/');
        }
        $event = Event::findOrFail($id);
        $event->status = "Done";
        if ($event->save()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }



    public function datatables(Request $request)
    {
        $event = Event::get();

        return DataTables::of($event)
            ->editColumn('date', function ($data) {

                return date("j F Y", strtotime($data->date));
            })
            ->make(true);
    }
}
