@extends('layouts.web')

@section('content')
<section>
    <div class="row">
        <div class="col-md-10 col-12 offset-md-1 py-3 px-4 px-md-0">
            <h3>Pengaduan</h3>
            <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />

            <div class="row mb-3 align-items-center">
                <div class="col-auto mb-1 pr-0">
                    <h6 class="mb-0">
                        Subject Pengaduan:
                    </h6>
                </div>
                <div class="col-auto mb-1">
                    <span class="font-weight-medium mr-2">{{ $ticketing->subject }}</span>
                    <span class="badge badge-pill border" v-bind:class="statusClass()">{{ $ticketing->status }}</span>
                </div>
            </div>

            <hr class="mb-4" />

            <div class="row mb-5">
                <div class="col-12 col-sm-auto mb-4">
                    <img v-if="ticket.member.image" v-bind:src="ticket.member.imageUrl" class="rounded-circle cover d-block mx-auto" style="width: 80px; height: 80px;" />
                    <fa-icon v-else size="5x" icon="user-circle" />
                </div>
                <div class="col">
                    <div class="font-weight-bold">{{ $ticketing->user->username }}</div>
                    <div class="font-italic text-muted mb-4">
                        {{date("j F Y",strtotime($ticketing->created_at))}}
                    </div>
                    <div class="row">
                        <div class="col mb-4">

                            {{ $ticketing->remarks }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h6>
                                <fa-icon icon="paperclip" class="mr-1" />
                                Tautan
                            </h6>
                            <img src="{{asset($ticketing->attachment)}}" alt="" class="img-fluid">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
