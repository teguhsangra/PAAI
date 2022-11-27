@extends('layouts.app')
@section('title')
    Rakomsis Profile - {{ ucwords(Auth::user()->username) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-icon card-header-success">
                    <div class="card-icon">
                        <i class="material-icons">perm_identity</i>
                    </div>
                    <h4 class="card-title">Edit Profile -
                        <small class="category">Complete your profile</small>
                    </h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => url($form_url), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="bmd-label-floating">Username</label>
                                <input type="text" class="form-control" name="username" value="{{ $profile->name }}">
                                <label class="error">{{ $errors->first('username') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email address</label>
                                <input type="email" class="form-control" name="email" value="{{ $profile->email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ $profile->phone }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Role</label>
                                <input type="text" class="form-control" name="role" value="{{ $profile->role }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Department</label>
                                <input type="text" class="form-control" name="department"
                                    value="{{ $profile->department }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Bio</label>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Write your own bio</label>
                                    <textarea class="form-control" rows="9" name="bio">{{ Auth::user()->bio }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="title">Avatar</h4>
                            <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                <div class="fileupload-new thumbnail">
                                    @if (!empty(Auth::user()))
                                        @if (Auth::user()->picture != null)
                                            <img src="{{ asset(Auth::user()->picture) }}" alt="other_doc_2" width="150">
                                        @else
                                            <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_2"
                                                width="150">
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_2"
                                            width="150">
                                    @endif
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail"
                                    style="width:150px;height:150px;"></div>
                                <div>
                                    <span class="btn btn-file btn-primary"><span class="fileupload-new"><i
                                                class="fa fa-picture-o"></i> Select image</span><span
                                            class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                        <input type="file" name="photo" />
                                    </span>
                                    <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                        <i class="fa fa-times"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Fill it if you want to change password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success pull-right">Update Profile</button>
                    <div class="clearfix"></div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo">
                        @if (Auth::user()->picture == null)
                            <img class="img" src="{{ asset('assets/img/default-avatar.png') }}" />
                        @else
                            <img class="img" src="{{ asset(Auth::user()->picture) }}" />
                        @endif
                    </a>
                </div>
                <div class="card-body">
                    <h6 class="card-category text-gray">{{ ucwords(Auth::user()->type) }}</h6>
                    <h4 class="card-title">{{ ucwords(Auth::user()->name) }}</h4>
                    <p class="card-description">
                        {{ Auth::user()->bio }}
                    </p>
                    <a href="#pablo" class="btn btn-success btn-round">Follow</a>
                </div>
            </div>
        </div>
    </div>
@endsection
