@extends('layouts.app')
@section('title')
V-Xibit User - {{ $user->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail User</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To User
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ $user->type }}</td>
                            </tr>
                            <tr>
                                <td>Bio</td>
                                <td>{!! $user->bio !!}</td>
                            </tr>
                            <tr>
                                <td>Photo</td>
                                <td>
                                    <div class="photo">
                                    @if(Auth::user()->photo == null)
                                        <img src="{{ asset('assets/img/default-avatar.png') }}" />
                                    @else
                                        <img src="{{ asset(Auth::user()->photo) }}" />
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
