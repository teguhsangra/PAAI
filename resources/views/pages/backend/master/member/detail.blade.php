@extends('layouts.app')
@section('title')
    PAAI - Member
@endsection
@section('content')
    <div class="toolbar mb-3">
        <a href="{{ url($url) }}" class="btn btn-rose">
            <i class="fa fa-arrow-left"></i> Back To Member
        </a>
    </div>
    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Detail Member</h4>

                </div>
                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-sm-auto col-4">
                            <div class="avatar avatar-xl position-relative">
                                @if (empty($member))
                                    <img src="{{ asset('not-available.jpg') }}" width="150"
                                        class="w-100 rounded-circle shadow-sm">
                                @else
                                    @if (!empty($member->picture))
                                        <img src="{{ asset($member->picture) }}" alt="bruce"
                                            class="w-100 rounded-circle shadow-sm">
                                    @else
                                        <img src="{{ asset('not-available.jpg') }}" width="150"
                                            class="w-100 rounded-circle shadow-sm">
                                    @endif
                                @endif

                            </div>
                        </div>
                        <div class="col-sm-auto col-12 my-auto">
                            <div class="h-100">
                                <h5 class="mb-1 font-weight-bolder">
                                    {{ $member->name }}
                                </h5>
                                <p class="mb-0 font-weight-normal text-sm">
                                    Nomor PAAI: {{ $member->code }}
                                </p>
                                <p class="mb-0 font-weight-normal text-sm">
                                    Nomor AAJI: {{ $member->aaji }}
                                </p>
                                <p class="mb-0 font-weight-normal text-sm">
                                    Expired date: {{ date('j F Y', strtotime($member->expired_date)) }}
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Member Info</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <span class="mb-2 text-xs">Company Name: <span
                                class="text-dark font-weight-bold ms-sm-2">{{ $member->company_name }}</span></span>
                        <span class="mb-2 text-xs">Email Address: <span
                                class="text-dark ms-sm-2 font-weight-bold">{{ $member->email }}</span></span>
                        <span class="mb-2 text-xs">Phone Number: <span
                                class="text-dark ms-sm-2 font-weight-bold">{{ $member->phone }}</span></span>
                        <span class="mb-2 text-xs">Birth date: <span
                                class="text-dark ms-sm-2 font-weight-bold">{{ date('j F Y', strtotime($member->birth_date)) }}</span></span>
                        <span class="mb-2 text-xs">Address: <span
                                class="text-dark ms-sm-2 font-weight-bold">{!! $member->address !!}</span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Membership Info</h5>
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-bordered" style="vertical-align: center;" id="member_info">
                        <thead>
                            <tr style="background-color: #385660 !important;color: #FFF;">
                                <th><b>Order Code</b></th>
                                <th><b>Product</b></th>
                                <th><b>Mulai tanggal</b></th>
                                <th><b>Sampai tanggal</b></th>
                                <th><b>Total</b></th>
                                <th><b>Status</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($member->booking as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ date('j F Y', strtotime($item->start_date)) }}</td>
                                    <td>{{ date('j F Y', strtotime($item->end_date)) }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>{{ $item->payment_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>



                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $("#member_info").DataTable();
    </script>
@endsection
