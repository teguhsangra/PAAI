@extends('layouts.web')

@section('content')
    @php
        $header_container_image = \App\Models\WebContent::where('name', 'header_container_image')->first();
        $header_container_front_image = \App\Models\WebContent::where('name', 'header_container_front_image')->first();
        $text_home_page = \App\Models\WebContent::where('name', 'text_home_page')->first();
        $image_home_page_1 = \App\Models\WebContent::where('name', 'image_home_page_1')->first();
        $image_home_page_2 = \App\Models\WebContent::where('name', 'image_home_page_2')->first();
        $image_home_page_3 = \App\Models\WebContent::where('name', 'image_home_page_3')->first();
    @endphp
    <div class="row">
        <div class="col bg-secondary">
            <div class="row">
                <div class="col bg-dark">
                    <div class="row">
                        <div class="col background shadow"
                            style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ $header_container_image->picture_1 }}) !important;">
                            @include('../components/header')
                            <div class="container-fluid">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 offset-1 col-10 py-5">
                                        <h1 class="display-4 text-warning mb-3">{{ $text_home_page->title }}</h1>
                                        <h3 class="text-white mb-5 font-weight-normal">{!! $text_home_page->desc !!}</h3>
                                        <a href="{{ url('register') }}" target="_blank"
                                            class="btn btn-lg bg-gradient-warning rounded-pill font-weight-bold text-uppercase py-3 mb-5">
                                            Bergabung Sekarang!
                                        </a>
                                    </div>
                                    <div class="col-lg d-none d-lg-block align-self-end">
                                        <img src="{{ $header_container_front_image->picture_1 }}"
                                            class="img-fluid float-right pr-5" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col shadow" style="padding: 7.5rem 6rem;" id="step-join">
                            <h1 class="text-white text-uppercase text-center" style="margin-bottom: 5rem;">3 Easy Steps To
                                Join</h1>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-3">
                                    <div class="number mx-auto mb-3 mb-md-5">
                                        <label class="h2 text-warning">
                                            1
                                        </label>
                                    </div>
                                    <img src="{{ asset('assets/front_end/img/internet.bbd856b2.svg') }}"
                                        class="d-lg-block  mx-auto mb-5 icon" />
                                    <p class="text-white text-center mb-5 mb-md-0">
                                        Buka browser masukan alamat ini <a href="{{ url('register') }}"
                                            target="_blank">Register</a>
                                    </p>
                                </div>
                                <div class="col-auto d-md-block  align-self-center">
                                    <img src="{{ asset('assets/front_end/img/triangle.933e9667.svg') }}" />
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="number mx-auto mb-3 mb-md-5">
                                        <label class="h2 text-warning">
                                            2
                                        </label>
                                    </div>
                                    <img src="{{ asset('assets/front_end/img/form.7a53272d.svg') }}"
                                        class="d-lg-block mx-auto mb-5 icon" />
                                    <p class="text-white text-center mb-5 mb-md-0">
                                        Lalu isi data diri anda lengkap dengan nomor AAJI/AAUI yang masih aktif
                                    </p>
                                </div>
                                <div class="col-auto d-md-block align-self-center">
                                    <img src="{{ asset('assets/front_end/img/triangle.933e9667.svg') }}" />
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="number mx-auto mb-3 mb-md-5">
                                        <label class="h2 text-warning">
                                            3
                                        </label>
                                    </div>
                                    <img src="{{ asset('assets/front_end/img/payment.d76f066c.svg') }}"
                                        class="d-lg-block mx-auto mb-5 icon" />
                                    <p class="text-white text-center">
                                        Lakukan pembayaran biaya keanggotaan Rp100.000,-/Thn
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col shadow" style="padding: 7.5rem 6rem;">
                    <div class="row justify-content-center align-items-center" style="margin-bottom: 7rem;">
                        <div class="col-8 col-sm-6 col-md-5 col-lg-4 offset-lg-1">
                            <img src="{{ $image_home_page_1->picture_1 }}" class="d-block mx-auto img-fluid mb-4" />
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-md-1">
                            <h1 class="text-warning mb-5 text-capitalize">{!! $image_home_page_1->desc !!}</h1>
                            <hr class="border-warning w-25 d-inline-block" />
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center" style="margin-bottom: 7rem;">
                        <div class="order-md-last col-8 col-sm-6 col-md-5 col-lg-4 offset-lg-1">
                            <img src="{{ $image_home_page_2->picture_1 }}" class="d-block mx-auto img-fluid mb-4" />
                        </div>
                        <div class="order-md-first col-12 col-md-6 col-lg-5 offset-md-1">
                            <h1 class="text-warning mb-5 text-capitalize">{!! $image_home_page_2->desc !!}</h1>
                            <hr class="border-warning w-25 d-inline-block" />
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-8 col-sm-6 col-md-5 col-lg-4 offset-lg-1">
                            <img src="{{ $image_home_page_3->picture_1 }}" class="d-block mx-auto img-fluid mb-4" />
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-md-1">
                            <h1 class="text-warning mb-5 text-capitalize">{!! $image_home_page_3->desc !!}</h1>
                            <hr class="border-warning w-25 d-inline-block" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" style="padding: 4rem;">
            <h4 class="text-center mb-4">Follow us on Instagram</h4>
            <div class="elfsight-app-7d2a31e4-3b38-4336-a705-334ddfa680f9"></div>
        </div>
    </div>
    <div class="modal fade show bg" id="modal_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal()"><i
                            class="material-icons">&times;</i></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12">
                            <div class="text-center">
                                <img src="https://paai.or.id/uploads/web_content/1658420132.png" alt=""
                                    class="img-fluid" width="90px" height="90px">
                            </div>
                        </div>
                        <div class="col-md-10 mt-5">
                            <h2 class="text-center text-justify">Sertifikat Penghargaan Profesi agen asuransi</h2>
                            <p class="text-center text-justify mt-5">Hanya khusus untuk peserta webinar Hut PAAI KE-6 yang
                                dapat
                                mendownload sertifikat penghargaan</p>
                        </div>

                        <div class="col-md-12 mt-5 text-center">
                            <a href="https://drive.google.com/drive/folders/1UaFw9mJ0qHpEZ7UvCo7tIk890g8-hxYI?usp=sharing"
                                class="btn btn-outline-primary">Download now</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://apps.elfsight.com/p/platform.js" defer></script>
    <script>
        @if (Auth::user())
            @if (Auth::user()->type == 'member')
                $('#modal_popup').show();
            @endif
        @endif
        function closeModal() {
            $('#modal_popup').hide();
        }
    </script>
@endsection
