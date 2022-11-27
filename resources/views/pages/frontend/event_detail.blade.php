@extends('layouts.web')

@section('content')
    <section>
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-12 blog-main ">

                    <div class="blog-post">
                        <h2 class="blog-post-title">{{ $event->name }}</h2>
                        <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />
                        <p class="blog-post-meta">
                            Tanggal: <b>{{ date('j F Y', strtotime($event->date)) }}</b>
                            <br>
                            Jam: <small class="text-muted">{{ $event->start_time }} - {{ $event->end_time }}</small>
                        </p>

                        <img src="{{ asset($event->image) }}" width="400px" height="400px" class="img-fluid rounded">
                        <p class="mt-2">{!! $event->desc !!}</p>
                    </div><!-- /.blog-post -->

                    @if ($event->status != 'Done')
                        <nav class="blog-pagination">
                            <a class="btn btn-outline-primary" onclick="Zoom('{{ $event->link }}')">Join Zoom</a>
                        </nav>
                    @endif
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
