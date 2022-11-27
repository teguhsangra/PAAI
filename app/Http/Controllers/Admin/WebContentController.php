<?php

namespace App\Http\Controllers\Admin;

use App\Models\WebContent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

class WebContentController extends Controller
{
    private $url = 'web_content';
    private $form_id = 'web_content_form';
    private $destinationPath = '/uploads/web_content/';
    protected $main_path;
    private $table_name = 'web_content';
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
        return view('pages.backend.web_content.index', $data);
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
        return view('pages.backend.web_content.editor', $data);
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
            'name' => 'required|unique:web_content',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {


            $web_content = new WebContent;
            $web_content->name = $request['name'];
            $web_content->type = $type;
            $web_content->title = $request['title'];
            $web_content->desc = $request['desc'];
            $web_content->link_location = $request['link_location'];
            $file = $request->file('picture_1');
            if ($request->hasFile('picture_1')) {
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
                $web_content->picture_1 = $this->destinationPath . '' . $photoName;
            }



            if ($web_content->save()) {
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
        $data['web_content'] = WebContent::findOrFail($id);
        return view('pages.backend.web_content.detail', $data);
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
        $data['web_content'] = WebContent::findOrFail($id);
        return view('pages.backend.web_content.editor', $data);
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
        $web_content = WebContent::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:web_content,name,' . $web_content->id,
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {


            $web_content->name = $request['name'];
            $web_content->type = $type;
            $web_content->title = $request['title'];
            $web_content->desc = $request['desc'];
            $web_content->link_location = $request['link_location'];

            $file1 = $request->file('picture_1');

            if ($request->hasFile('picture_1')) {
                $photoName = time() . '.' . $file1->getClientOriginalExtension();

                $path = public_path($this->destinationPath);
                HomeController::check_exist_folder($path);
                $path = $path . $photoName;

                if ($file1->getSize() > 2000000) {
                    Image::make($file1->getRealPath())->resize(2024, 2024, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path);
                } else {
                    Image::make($file1->getRealPath())->save($path);
                }

                if (!empty($web_content->picture_1)) {
                    File::Delete(public_path($web_content->picture_1));
                }

                $web_content->picture_1 = $this->destinationPath . '' . $photoName;
            }






            if ($web_content->save()) {
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
        $web_content = WebContent::findOrFail($id);
        if ($web_content->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }



    public function datatables(Request $request)
    {
        $web_content = WebContent::get();

        return DataTables::of($web_content)
            ->make(true);
    }
}
