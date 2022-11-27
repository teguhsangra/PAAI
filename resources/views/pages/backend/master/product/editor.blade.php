@extends('layouts.app')
@section('title')
PAAI - Product
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Product Hall Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" id="code" class="form-control" @if(!empty($product)) value="{{ $product->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($product)) value="{{ $product->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <input type="text" id="format_price" name="format_price" class="form-control" onchange="changeToCurrencyFormat('format_price','price')" @if(!empty($product)) value="{{ number_format($product->price, 0,',','.') }}" @else value="{{ number_format(old('price'), 0,',','.') }}" @endif>
                            <input type="hidden" id="price" name="price" @if(!empty($product)) value="{{ $product->price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Price Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('price_type') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="price_type" data-size="6" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                <option value="yearly" @if(!empty($product)) @if($product->price_type == "yearly") selected @endif @endif>Yearly</option>
                                <option value="monthly" @if(!empty($product)) @if($product->price_type == "monthly") selected @endif @endif>Monthly</option>
                                <option value="daily" @if(!empty($product)) @if($product->price_type == "daily") selected @endif @endif>Daily</option>
                                <option value="hourly" @if(!empty($product)) @if($product->price_type == "hourly") selected @endif @endif>Hourly</option>
                                <option value="halfday" @if(!empty($product)) @if($product->price_type == "halfday") selected @endif @endif>Halfday</option>
                                <option value="single" @if(!empty($product)) @if($product->price_type == "single") selected @endif @endif>Single</option>
                            </select>
                            <label class="error">{{ $errors->first('price_type') }}</label>
                        </div>
                    </div>
                </div>

            {{ Form::close() }}
            </div>
            <div class="card-footer">
                <a href="{{ url($url)}}" class="col-md-2 col-sm-offset-3 btn-lg btn btn-warning">Back</a>
                <button type="button" class="col-md-4 col-sm-offset-1 btn-lg btn btn-primary" data-toggle="modal" data-target="#accessGroupModal">{{ $button_name }}</button>

                <div class="modal fade modal-mini modal-primary" id="accessGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-small">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to do continue ?</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                <button type="button" class="btn btn-success btn-link" onclick="submitForm('{{ $form_id }}')">Yes
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
