<?php

namespace App\Http\Controllers\Admin\Master;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

class ProductController extends Controller
{
    private $url = 'master/product';
    private $form_id = 'product_form';
    protected $main_path;
    private $table_name = 'products';
    private $prefix_name = 'PRO';
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
            return Redirect::to('admin/profile');
        }
        $data['url'] = $this->url;
        return view('pages.backend.master.product.index', $data);
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
            return Redirect::to('admin/profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        return view('pages.backend.master.product.editor', $data);
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
            return Redirect::to('admin/profile');
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products',
            'name' => 'required|unique:products',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $product = new Product;
            $product->code = $request['code'];
            $product->name = $request['name'];
            $product->price = $request['price'];
            $product->price_type = $request['price_type'];
            $product->created_by = Auth::user()->username;
            if ($product->save()) {
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
            return Redirect::to('admin/profile');
        }
        $data['url'] = $this->url;
        $data['product'] = Product::findOrFail($id);
        return view('pages.backend.master.product.detail', $data);
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
            return Redirect::to('admin/profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['product'] = Product::findOrFail($id);
        return view('pages.backend.master.product.editor', $data);
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
            return Redirect::to('admin/profile');
        }
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required|unique:products,name,'  . $product->id,
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $product->code = $request['code'];
            $product->name = $request['name'];
            $product->price = $request['price'];
            $product->price_type = $request['price_type'];
            $product->updated_by = Auth::user()->name;
            if ($product->save()) {
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
            return Redirect::to('admin/profile');
        }
        $product = Product::findOrFail($id);
        if ($product->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }



    public function datatables(Request $request)
    {
        $product = Product::get();

        return DataTables::of($product)
        ->editColumn('price', function ($data) {
            return number_format($data->price, 0, ',', '.');
        })
        ->make(true);
    }


}
