@extends('layouts.web')

@section('content')
    <style>
        .tabs a.nav-link:not(.active) {
            color: #ECAC3B;
        }
    </style>

    <section>
       <div class="container mt-5 mb-5">
            <div class="card">
                <div class="card-header">
                    <h3>Cara Pembayaran</h3>
                            <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-8 col-lg-9 col-md-10 col-12 offset-md-1 py-3">


                            <h4 class="mb-3">Petunjuk Pembayaran Iuran Keanggotan PAAI</h4>

                            <div class="tabs">
                                <!---->
                                <div class="">
                                    <ul role="tablist" class="nav nav-tabs">
                                        <!---->
                                        @foreach ($bank as $key => $item)
                                        <li role="presentation" class="nav-item">
                                            <a class="nav-link {{ $key==0 ? 'active' : ''}}" id="{{$item->id}}-tab" data-toggle="tab" href="#{{$item->id}}" role="tab"
                                                aria-controls="{{$item->id}}" aria-selected="true">{{$item->bank_name}}
                                            </a>
                                        </li>
                                        @endforeach
                                        <!---->
                                    </ul>
                                </div>
                                <div class="tab-content border-bottom border-left border-right p-4 rounded-bottom mb-5">
                                    @foreach ($bank as $key => $item)
                                    <div class="tab-pane {{ $key==0 ? 'active' : ''}}" id="{{$item->id}}" role="tabpanel" aria-labelledby="{{$item->id}}-tab">
                                        {!! $item->desc !!}
                                    </div>
                                    @endforeach

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
       </div>
    </section>
@endsection
