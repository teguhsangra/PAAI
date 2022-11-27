@extends('layouts.app')
@section('title')
    PAAI - Web Content {{ $web_content->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Detail Web Content</h4>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <a href="{{ url($url) }}" class="btn btn-rose">
                            <i class="fa fa-arrow-left"></i> Back To Web Content
                        </a>
                    </div>
                    <div class="material-datatables">
                        <table class="table table-bordered" width="100%" style="width:100%">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $web_content->name }}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $web_content->type }}</td>
                                </tr>
                                <tr>
                                    <td>Title</td>
                                    <td>{{ $web_content->title }}</td>
                                </tr>
                                <tr>
                                    <td>Default Photo 1</td>
                                    <td>
                                        @if (!empty($web_content->picture_1))
                                            <img src="{{ asset($web_content->picture_1) }}" alt="with_holding_tax"
                                                width="250">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Default Photo 2</td>
                                    <td>
                                        @if (!empty($web_content->picture_2))
                                            <img src="{{ asset($web_content->picture_2) }}" alt="with_holding_tax"
                                                width="250">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Desc</td>
                                    <td>{{ $web_content->desc }}</td>
                                </tr>
                                <tr>
                                    <td>link</td>
                                    <td>{{ $web_content->link_location }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
