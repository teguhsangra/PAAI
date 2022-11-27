@extends('layouts.web')

@section('content')
    <section>
        <div class="container mt-2 mb-2">
            <div class="row">
                <div class="col-md-12">
                    <h3>
                        Event
                    </h3>
                    <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />
                </div>
            </div>
        </div>
        <div class="container mt-2 mb-5">

            <div class="row">
                @foreach ($event as $item)
                    <div class="col-md-4">
                        <div class="card mb-4 box-shadow bg-light">
                            <img class="card-img-top" src="{{ asset($item->image) }}" height="300px">
                            <div class="card-body">
                                <h3>{{ $item->name }}</h3>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ date('j F Y', strtotime($item->date)) }}</small>
                                    <small class="text-muted">{{ $item->start_time }} - {{ $item->end_time }}</small>
                                </div>
                                <p class="card-text mt-2">
                                    {!! Str::of($item->desc)->limit(200) !!}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ url('funfriday') }}/{{ $item->slug }}"
                                            class="btn btn-sm btn-outline-secondary">View</a>
                                        @if ($item->status != 'Done')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="Zoom('{{ $item->link }}')">Join
                                                Zoom</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            <div class="row justify-content-end">
                <div class="col-auto">
                    {{ $event->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function Zoom(url) {

            var printWindow = window.open(url);
        }
    </script>
@endsection
