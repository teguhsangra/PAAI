@extends('layouts.web')

@section('content')
    <style>
        .card {
            width: 260px;
            height: 300px;
        }

        .container-avatar {
            position: relative;
            z-index: 1;
            min-height: 80px;
            width: 120px;
            height: 120px;
        }
    </style>
    <section>
        <div class="row">
            <div class="col-md-10 col-12 offset-md-1 py-3 px-4 px-md-0">
                <h3>Member List</h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />

                <div class="row justify-content-end">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                        <form method="get" action="{{ url('member') }}">
                            <div class="input-group mb-3">
                                <input name="keyword" type="text" class="form-control" placeholder="Search..."
                                    @if (!empty($keyword)) value="{{ $keyword }}" @endif />
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col px-5 mb-5">
                <div class="row justify-content-center mb-4">
                    @foreach ($member as $item)
                        <div class="col-auto mb-3">
                            <div class="card bg-light shadow">
                                <div class="container-avatar">
                                    @if ($item->picture)
                                        <img src="{{ asset($item->picture) }}"
                                            class="ml-4 mt-4 avatar rounded-circle border border-dark"
                                            style="width: 100px; height: 100px;">
                                    @else
                                        <img src="{{ asset('assets/img/logo_paai.png') }}"
                                            class="ml-4 mt-4 avatar rounded-circle border border-dark"
                                            style="width: 100px; height: 100px;">
                                    @endif

                                </div>
                                <div class="h-100 bg-secondary rounded-bottom px-3 py-2 small">
                                    <p class="text-white font-weight-medium mb-5 text-right text-truncate"
                                        style="margin-left: 120px;">
                                        {{ $item->code }}
                                    </p>

                                    <h6 class="text-warning text-truncate d-block mb-2">
                                        {{ $item->name }}
                                    </h6>

                                    <p class="mb-1 text-white text-truncate">
                                        <i class="fa fa-envelope text-warning"></i><a href="#">
                                            {{ $item->email }}</a>
                                    </p>
                                    <p v-if="user.member" class="mb-1 text-white text-truncate">
                                        <i class="fa fa-mobile-alt text-warning"></i><a href="#">
                                            {{ $item->phone }}</a>
                                    </p>
                                    <p v-if="user.member" class="text-white text-truncate">
                                        <i class="fa fa-building text-warning"></i> {{ $item->company_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row justify-content-end">
                    <div class="col-auto">

                        {{ $member->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
