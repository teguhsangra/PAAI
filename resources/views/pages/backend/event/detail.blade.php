@extends('layouts.app')
@section('title')
    PAAI - Fun Friday {{ $event->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Detail Fun Friday</h4>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <a href="{{ url($url) }}" class="btn btn-rose">
                            <i class="fa fa-arrow-left"></i> Back To Fun Friday
                        </a>
                    </div>
                    <div class="material-datatables">
                        <table class="table table-bordered" width="100%" style="width:100%">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $event->name }}</td>
                                </tr>
                                <tr>
                                    <td>Start time</td>
                                    <td>{{ $event->start_time }}</td>
                                </tr>
                                <tr>
                                    <td>End time</td>
                                    <td>{{ $event->end_time }}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>{{ $event->date }}</td>
                                </tr>
                                <tr>
                                    <td>Default Photo</td>
                                    <td>
                                        @if (!empty($event->image))
                                            <img src="{{ asset($event->image) }}" alt="with_holding_tax" width="250">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Desc</td>
                                    <td>{!! $event->desc !!}</td>
                                </tr>
                                <tr>
                                    <td>Link zoom</td>
                                    <td>{!! $event->link !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
