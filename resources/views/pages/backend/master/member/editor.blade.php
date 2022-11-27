@extends('layouts.app')
@section('title')
PAAI Member - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Member Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="title">Avatar</h4>
                        <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                            <div class="fileupload-new thumbnail">
                                @if(empty($member))
                                    <img src="{{ asset('not-available.jpg') }}" width="150">
                                @else
                                    @if(!empty($member->picture))
                                        <img src="{{ asset($member->picture) }}" width="150">
                                    @else
                                        <img src="{{ asset('not-available.jpg') }}" width="150">
                                    @endif
                                @endif

                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                            <div>
                                <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                    <input type="file" name="photo" />
                                </span>
                                <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Nama Lengkap</label>
                            <input type="text" name="username" class="form-control" @if(!empty($member)) value="{{ $member->name }}" @else value="{{ old('username') }}" @endif>
                            <label class="error">{{ $errors->first('username') }}</label>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Email</label>
                            <input type="text" name="email" class="form-control" @if(!empty($member)) value="{{ $member->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Nomor telepon</label>
                            <input type="text" name="phone" class="form-control" @if(!empty($member)) value="{{ $member->phone }}" @else value="{{ old('phone') }}" @endif>
                            <label class="error">{{ $errors->first('phone') }}</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Nama Perusahaan</label>
                            <input type="text" name="company_name" class="form-control" @if(!empty($member)) value="{{ $member->company_name }}" @else value="{{ old('company_name') }}" @endif>
                            <label class="error">{{ $errors->first('company_name') }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('aaji') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">No AAJI/AAUI/UMUM</label>
                            <input type="text" name="aaji" class="form-control" @if(!empty($member)) value="{{ $member->aaji }}" @else value="{{ old('aaji') }}" @endif>
                            <label class="error">{{ $errors->first('aaji') }}</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('birth_date') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Tanggal Lahir</label>
                            <input type="text" name="birth_date" id="birth_date" class="form-control datepicker text-center" placeholder="Birth Date"  @if(!empty($member)) value="{{ date('m/d/Y', strtotime($member->birth_date)) }}" @else value="{{ old('birth_date') }}" @endif>
                            <label class="error">{{ $errors->first('birth_date') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                            <label class="bmd-label-floating">Address</label>
                            <textarea class="form-control" rows="9" name="address">@if(!empty($member)){{ $member->address }}@endif</textarea>
                            <label class="error">{{ $errors->first('address') }}</label>
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


