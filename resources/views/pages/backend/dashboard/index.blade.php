@extends('layouts.app')
@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">account_circle</i>
                            </div>
                            <p class="card-category">Member</p>
                            <h3 class="card-title">{{ $member }}
                                <small>person</small>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons text-danger">update</i>
                                Just Updated.
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">account_circle</i>
                            </div>
                            <p class="card-category">Member Active</p>
                            <h3 class="card-title">{{ $member_active }}
                                <small>person</small>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons text-danger">update</i>
                                Just Updated.
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">account_circle</i>
                            </div>
                            <p class="card-category">Member Not Active</p>
                            <h3 class="card-title">{{ $member_not_active }}
                                <small>person</small>
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons text-danger">update</i>
                                Just Updated.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
