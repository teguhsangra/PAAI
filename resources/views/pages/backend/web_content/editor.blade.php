@extends('layouts.app')
@section('title')
    PAAI Web Content - Editor
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">library_books</i>
                    </div>
                    <h4 class="card-title">Web Content Form</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input type="text" name="name" class="form-control"
                                    @if (!empty($web_content)) value="{{ $web_content->name }}"  readonly  value="{{ old('name') }}" @endif>
                                <label class="error">{{ $errors->first('name') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Type</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <select id="type" class="selectpicker form-control" name="type" data-size="6"
                                    data-style="select-with-transition" title="Single Select" data-show-subtext="true"
                                    data-live-search="true" onchange="showForm(this.value);">
                                    <option disabled selected>Select Your Option</option>
                                    <option value="text"
                                        @if (!empty($web_content)) @if ($web_content->type == 'text') selected @endif
                                        @endif>Text</option>
                                    <option value="image"
                                        @if (!empty($web_content)) @if ($web_content->type == 'image') selected @endif
                                        @endif>Image</option>
                                    <option value="map"
                                        @if (!empty($web_content)) @if ($web_content->type == 'map') selected @endif
                                        @endif>map</option>
                                </select>
                                <label class="error">{{ $errors->first('type') }}</label>
                            </div>
                        </div>
                    </div>
                    <div id="formText" style="display: none;">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Text</label>
                            <div class="col-sm-10">
                                <div class="form-group bmd-form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <input type="text" name="title" class="form-control"
                                        @if (!empty($web_content)) value="{{ $web_content->title }}" @else value="{{ old('title') }}" @endif>
                                    <label class="error">{{ $errors->first('name') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="formImage" style="display: none;" class="container mt-5">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                    <div class="fileupload-new thumbnail">
                                        @if (!empty($web_content))
                                            @if ($web_content->picture_1 != null)
                                                <img src="{{ asset($web_content->picture_1) }}" alt="picture_1"
                                                    width="150">
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="picture_1"
                                                    width="150">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="picture_1"
                                                width="150">
                                        @endif
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail"
                                        style="width:150px;height:150px;"></div>
                                    <div>
                                        <span class="btn btn-file btn-primary"><span class="fileupload-new"><i
                                                    class="fa fa-picture-o"></i> Select image</span><span
                                                class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                            <input type="file" id="picture_1" name="picture_1" />
                                            <input type="hidden" id="picture_1_for_update"
                                                @if (!empty($web_content)) value="{{ $web_content->picture_1 }}" @else value="" @endif>
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
                    <div id="formDesc" style="display: none">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Desc</label>
                            <div class="col-sm-10">
                                <div class="form-group bmd-form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                    <textarea class="form-control" rows="9" name="desc">
                                    @if (!empty($web_content)){{ $web_content->desc }}@endif
                                    </textarea>
                                    <label class="error">{{ $errors->first('desc') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="formMap" style="display: none;">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Url Map</label>
                            <div class="col-sm-10">
                                <div
                                    class="form-group bmd-form-group{{ $errors->has('link_location') ? ' has-error' : '' }}">
                                    <input type="text" name="link_location" class="form-control"
                                        @if (!empty($web_content)) value="{{ $web_content->link_location }}" @else value="{{ old('link_location') }}" @endif>
                                    <label class="error">{{ $errors->first('name') }}</label>
                                </div>
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
@section('js')
    <script>
        @if (!empty($web_content))
            showForm('{{ $web_content->type }}')
        @endif
        function showForm(value) {
            if (value == "text") {
                $('#formText').show();
                $('#formImage').hide();
                $('#formDesc').show();
                $('#formMap').hide();
            } else if (value == "image") {
                $('#formText').hide();
                $('#formImage').show();
                $('#formDesc').show();
                $('#formMap').hide();
            } else {
                $('#formText').hide();
                $('#formImage').hide();
                $('#formDesc').hide();
                $('#formMap').show();
            }
        }
    </script>
@endsection
