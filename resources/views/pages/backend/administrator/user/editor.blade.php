@extends('layouts.app')
@section('title')
V-Xibit User - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">User Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <input type="text" name="username" class="form-control" @if(!empty($user)) value="{{ $user->username }}" @else value="{{ old('username') }}" @endif>
                            <label class="error">{{ $errors->first('username') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control" @if(!empty($user)) value="{{ $user->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bio</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                            <textarea class="form-control" rows="9" name="bio">@if(!empty($user)){{ $user->bio }}@endif</textarea>
                            <label class="error">{{ $errors->first('bio') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <select class="selectpicker" name="type" data-size="5" data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                <option value="admin" @if(!empty($user)) @if($user->type == "admin") selected @endif @endif>Admin</option>
                            </select>
                            <label class="error">{{ $errors->first('type') }}</label>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" name="password" class="form-control" @if(!empty($user)) value="{{ $user->password }}" @else value="{{ old('password') }}" @endif>
                            <label class="error">{{ $errors->first('password') }}</label>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <label class="col-sm-2 col-form-label">Re-Password</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('re_password') ? ' has-error' : '' }}">
                            <input type="password" name="email" class="form-control" @if(!empty($user)) value="{{ $user->re_password }}" @else value="{{ old('re_password') }}" @endif>
                            <label class="error">{{ $errors->first('re_password') }}</label>
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
