@php
$footer_text = \App\Models\WebContent::where('name', 'footer_text')->first();
$phone = \App\Models\WebContent::where('name', 'phone')->first();
$email = \App\Models\WebContent::where('name', 'email')->first();
$map = \App\Models\WebContent::where('name', 'map')->first();
$logo_web_white = \App\Models\WebContent::where('name', 'logo_web_white')->first();
@endphp
<section>
    <footer class="bg-dark container-fluid text-white">
        <div class="row justify-content-center align-items-center">
            <div class="order-md-last col-12 col-md">
                <div class="row justify-content-center align-items-center">
                    <div class="order-xl-last col-auto">
                        <img src="{{ $logo_web_white->picture_1 }} " class="mb-4 mb-xl-0" style="width: 10rem;">
                    </div>
                    <div class="order-xl-first col-12 col-xl px-4 mb-3 mb-md-0">
                        <address>
                            <h4 class="mb-4">{{ $footer_text->title }}</h4>
                            <p class="h5 font-weight-normal mb-1">{!! $footer_text->desc !!}</p>

                            <p class="mb-1">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope"
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                    class="svg-inline--fa fa-envelope fa-w-16 fa-fw">
                                    <path fill="currentColor"
                                        d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
                                        class="">
                                    </path>
                                </svg>
                                <a href="mailto:official@paai.or.id">{{ $email->title }}</a>
                            </p>
                            <p>
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mobile-alt"
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                                    class="svg-inline--fa fa-mobile-alt fa-w-10 fa-fw">
                                    <path fill="currentColor"
                                        d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z"
                                        class="">
                                    </path>
                                </svg>
                                <a href="tel:+62 815-1718-9461">{{ $phone->title }}</a>
                            </p>
                        </address>
                    </div>
                </div>
            </div>
            <div class="order-md-first">
                <iframe src="{{ $map->link_location }}" height="300" width="300" frameborder="0"
                    allowfullscreen="allowfullscreen" aria-hidden="false" tabindex="0" style="border: 0px;">
                </iframe>
            </div>
        </div>
    </footer>

</section>
