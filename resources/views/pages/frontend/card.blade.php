@extends('layouts.web')

@section('content')
    <style>
        .card {
            width: 551px;
            height: 340px;
            padding: 2rem 2rem 1rem;
            background: url({{ asset('assets/front_end/img/background_card.2cac78af.png') }});
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <section>
        <div class="jumbotron jumbotron-fluid bg-transparent text-white px-md-3 px-0">
            <div class="container-fluid px-md-5 px-0">
                <h1 class="text-black mb-4 text-center">ID Card Member</h1>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-auto mx-auto mb-5 overflow-auto">
                            <div class="card" ref="card" id="frontCard">
                                <div class="row">
                                    <div class="col">
                                        <h5>{{ $member->name }}</h5>
                                        <label class="h5 font-weight-light mb-0">{{ $member->code }}</label>
                                    </div>
                                    <div class="col-auto">
                                        <img src="{{ asset('assets/front_end/img/logo_alt.290055b4.png') }}" alt="Logo"
                                            style="width: 80px;" />
                                    </div>
                                </div>
                                <div class="row justify-content-between my-auto">
                                    <div class="col">
                                        @if ($member->picture != null)
                                            <img src="{{ $member->picture }}" style="height: 140px;border-radius: 14px;"
                                                class="img-fluid cover" />
                                        @endif
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <img src="{{ asset('assets/front_end/img/qr.png') }}"
                                            style="height: 140px;border-radius: 14px;" class="img-fluid cover" />

                                    </div>
                                </div>
                                <div class="row justify-content-between" style="font-size: 9px;">
                                    <div class="col-auto">
                                        <p class="font-weight-medium mb-0">Perkumpulan Agen Asuransi Indonesia</p>
                                    </div>
                                    <div class="col-auto px-0">
                                        <i class="fa fa-mobile-alt"></i> {{ $member->phone }}
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-envelope"></i> {{ $member->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-auto mx-auto mb-5 overflow-auto">
                            <div class="card" ref="card" id="backCard">
                                <div class="row justify-content-between" style="font-size: 9px;">
                                    <div class="col-auto">
                                        <p class="font-weight-medium mb-0">Perkumpulan Agen Asuransi Indonesia</p>
                                    </div>
                                    <div class="col-auto px-0">
                                        Berlaku hingga: {{ date('j F Y', strtotime($member->expired_date)) }}
                                    </div>

                                </div>
                                <h1 class="text-white mb-2 mt-2" style="font-size: 16px">Merchant</h1>
                                <div class="row ">
                                    @foreach ($merchant as $item)
                                        <div class="col-md-2">
                                            <img src="{{ asset($item->picture) }}" style="height: 60px;border-radius: 8px;"
                                                class="img-fluid cover" />
                                            <h5 class="text-white mt-1 text-center" style="font-size: 8px">
                                                {{ $item->name }}</h5>
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-center">
                    <div class="row ">
                        <div class="col-md-6">
                            <button type="button" onclick="downloadimage()"
                                class="btn bg-gradient-warning rounded-pill font-weight-bold text-uppercase py-3 px-5">
                                Download Kartu
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="benefits"
                                class="btn bg-gradient-info rounded-pill font-weight-bold text-uppercase py-3 px-5">Bennefits</button>
                        </div>
                    </div>


                </div>


            </div>
        </div>
        <div class="container mt-2 mb-5" id="view_benefit">
            <h1 class="text-black mb-4 text-center">Merchant</h1>
            <div class="owl-carousel owl-theme">
                @foreach ($merchant as $item)
                    <div class="item text-center">
                        <img src="{{ asset($item->picture) }}" style="width: 150px;height: 150px;" class="mx-auto d-block">
                        <div class="caption mt-3">
                            <h5 class="animated bounce ">{{ $item->name }}</h5>
                            <p>{!! $item->desc !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $("#benefits").click(function() {
            $('html,body').animate({
                    scrollTop: $("#view_benefit").offset().top
                },
                'slow');
        });

        function downloadimage() {

            var container = document.getElementById("frontCard"); // full page

            html2canvas(container, {
                allowTaint: true
            }).then(function(canvas) {

                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "front_card.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();
            });

            var container2 = document.getElementById("backCard"); // full page
            html2canvas(container2, {
                allowTaint: true
            }).then(function(canvas) {

                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "back_card.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();
            });
        }
        $(document).ready(function() {
            var owl = $('.owl-carousel');

            owl.owlCarousel({
                items: '{{ sizeof($merchant) }}',
                loop: true,
                autoplay: true,
                autoplayTimeout: 3500,
                nav: true,
                margin: 10,
            });

            owl.on('changed.owl.carousel', function(event) {
                var item = event.item.index - 2; // Position of the current item
                $('h1').removeClass('animated bounce');
                $('.owl-item').not('.cloned').eq(item).find('h1').addClass('animated bounce');
            });

        });
    </script>
@endsection
