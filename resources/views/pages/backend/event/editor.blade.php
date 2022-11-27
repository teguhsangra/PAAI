@extends('layouts.app')
@section('title')
    PAAI Fun Friday - Editor
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">library_books</i>
                    </div>
                    <h4 class="card-title">Fun Friday Form</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input type="text" name="name" class="form-control"
                                    @if (!empty($event)) value="{{ $event->name }}"    value="{{ old('name') }}" @endif>
                                <label class="error">{{ $errors->first('name') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-5">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                    <div class="fileupload-new thumbnail">
                                        @if (!empty($event))
                                            @if ($event->image != null)
                                                <img src="{{ asset($event->image) }}" alt="image" width="150">
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="image"
                                                    width="150">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="image"
                                                width="150">
                                        @endif
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail"
                                        style="width:150px;height:150px;"></div>
                                    <div>
                                        <span class="btn btn-file btn-primary"><span class="fileupload-new"><i
                                                    class="fa fa-picture-o"></i> Select image</span><span
                                                class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                            <input type="file" id="image" name="image" />
                                            <input type="hidden" id="image"
                                                @if (!empty($event)) value="{{ $event->picture_1 }}" @else value="" @endif>
                                        </span>
                                        <a href="#" class="btn fileupload-exists btn-danger"
                                            data-dismiss="fileupload">
                                            <i class="fa fa-times"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="start_to_end">
                        <label class="col-sm-2 col-form-label">Date & Time</label>
                        <div class="col-sm-10">
                            <div class="row" id="datepicker">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="date" id="start_date"
                                            class="form-control datepicker text-center" placeholder="Start Date"
                                            @if (!empty($event)) value="{{ date('m/d/Y', strtotime($event->date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="timepicker">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="start_time" id="start_time"
                                            class="form-control timepicker text-center" placeholder="Start Time"
                                            @if (!empty($event)) value="{{ date('H:i', strtotime($event->start_time)) }}" @endif>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="end_time" id="end_time"
                                            class="form-control timepicker text-center" placeholder="End Time"
                                            @if (!empty($event)) value="{{ date('H:i', strtotime($event->end_time)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="signed_date" id="signed_date"
                                            class="form-control datepicker text-center" placeholder="Signed Date"
                                            @if (!empty($event)) value="{{ date('m/d/Y', strtotime($event->signed_date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Desc</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="9" name="desc">
                                @if (!empty($event))
{{ $event->desc }}
@endif
                                </textarea>
                                <label class="error">{{ $errors->first('desc') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Link zoom</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                                <input type="text" name="link" class="form-control"
                                    @if (!empty($event)) value="{{ $event->link }}" @else value="{{ old('link') }}" @endif>
                                <label class="error">{{ $errors->first('name') }}</label>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="card-footer">
                    <a href="{{ url($url) }}" class="col-md-2 col-sm-offset-3 btn-lg btn btn-warning">Back</a>
                    <button type="button" class="col-md-4 col-sm-offset-1 btn-lg btn btn-primary" data-toggle="modal"
                        data-target="#accessGroupModal">{{ $button_name }}</button>

                    <div class="modal fade modal-mini modal-primary" id="accessGroupModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to do continue ?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                    <button type="button" class="btn btn-success btn-link"
                                        onclick="submitForm('{{ $form_id }}')">Yes
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
