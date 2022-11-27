<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/front_end/favicon/apple-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/front_end/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('assets/front_end/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PAAI</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!-- Fonts and icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('assets/front_end/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front_end/css/vendor.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <script src="https://cdn.tiny.cloud/1/dhxcodr64xti1nkdga02mhzm7h5z9pxyknajlm6bl49ucbpy/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>


</head>

<body>
    @if (Request::segment(1) != '')
        @include('../components/header')
    @endif
    <div class="container-fluid">
        @yield('content')
    </div>
    @include('../components/footer')
    <input class='chatMenu hidden' id='offchatMenu' type='checkbox' />
    <label class='chatButton' for='offchatMenu'>
        <svg class='svg-1' viewBox='0 0 32 32'>
            <g>
                <path
                    d='M16,2A13,13,0,0,0,8,25.23V29a1,1,0,0,0,.51.87A1,1,0,0,0,9,30a1,1,0,0,0,.51-.14l3.65-2.19A12.64,12.64,0,0,0,16,28,13,13,0,0,0,16,2Zm0,24a11.13,11.13,0,0,1-2.76-.36,1,1,0,0,0-.76.11L10,27.23v-2.5a1,1,0,0,0-.42-.81A11,11,0,1,1,16,26Z'>
                </path>
                <path
                    d='M19.86,15.18a1.9,1.9,0,0,0-2.64,0l-.09.09-1.4-1.4.09-.09a1.86,1.86,0,0,0,0-2.64L14.23,9.55a1.9,1.9,0,0,0-2.64,0l-.8.79a3.56,3.56,0,0,0-.5,3.76,10.64,10.64,0,0,0,2.62,4A8.7,8.7,0,0,0,18.56,21a2.92,2.92,0,0,0,2.1-.79l.79-.8a1.86,1.86,0,0,0,0-2.64Zm-.62,3.61c-.57.58-2.78,0-4.92-2.11a8.88,8.88,0,0,1-2.13-3.21c-.26-.79-.25-1.44,0-1.71l.7-.7,1.4,1.4-.7.7a1,1,0,0,0,0,1.41l2.82,2.82a1,1,0,0,0,1.41,0l.7-.7,1.4,1.4Z'>
                </path>
            </g>
        </svg>
        <svg class='svg-2' viewBox='0 0 512 512'>
            <path
                d='M278.6 256l68.2-68.2c6.2-6.2 6.2-16.4 0-22.6-6.2-6.2-16.4-6.2-22.6 0L256 233.4l-68.2-68.2c-6.2-6.2-16.4-6.2-22.6 0-3.1 3.1-4.7 7.2-4.7 11.3 0 4.1 1.6 8.2 4.7 11.3l68.2 68.2-68.2 68.2c-3.1 3.1-4.7 7.2-4.7 11.3 0 4.1 1.6 8.2 4.7 11.3 6.2 6.2 16.4 6.2 22.6 0l68.2-68.2 68.2 68.2c6.2 6.2 16.4 6.2 22.6 0 6.2-6.2 6.2-16.4 0-22.6L278.6 256z'>
            </path>
        </svg>
    </label>

    <div class='chatBox'>
        <div class='chatContent'>
            <div class='chatHeader'>
                <svg viewbox='0 0 32 32'>
                    <path
                        d='M24,22a1,1,0,0,1-.64-.23L18.84,18H17A8,8,0,0,1,17,2h6a8,8,0,0,1,2,15.74V21a1,1,0,0,1-.58.91A1,1,0,0,1,24,22ZM17,4a6,6,0,0,0,0,12h2.2a1,1,0,0,1,.64.23L23,18.86V16.92a1,1,0,0,1,.86-1A6,6,0,0,0,23,4Z'>
                    </path>
                    <rect height='2' width='2' x='19' y='9'></rect>
                    <rect height='2' width='2' x='14' y='9'></rect>
                    <rect height='2' width='2' x='24' y='9'></rect>
                    <path
                        d='M8,30a1,1,0,0,1-.42-.09A1,1,0,0,1,7,29V25.74a8,8,0,0,1-1.28-15,1,1,0,1,1,.82,1.82,6,6,0,0,0,1.6,11.4,1,1,0,0,1,.86,1v1.94l3.16-2.63A1,1,0,0,1,12.8,24H15a5.94,5.94,0,0,0,4.29-1.82,1,1,0,0,1,1.44,1.4A8,8,0,0,1,15,26H13.16L8.64,29.77A1,1,0,0,1,8,30Z'>
                    </path>
                </svg>
                <div class='chatTitle'>Silahkan chat dengan tim kami <span>Admin akan membalas dalam beberapa
                        menit</span></div>
            </div>
            <div class='chatText'>
                <span>Halo, Ada yang bisa kami bantu?</span>
                <span class='typing'>...</span>
            </div>
        </div>

        <a class='chatStart'
            href='https://api.whatsapp.com/send?phone=6281517189461&text=Hallo Admin PAAI,%20Saya%20ingin%20bertanya'
            rel='nofollow noreferrer' target='_blank'>
            <span>Mulai chat...</span>
        </a>
    </div>


    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/bootstrap.js') }}"></script>
    <!-- DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/front_end/js/html2canvas.js') }}"></script>
    <script>
        function gdrive() {
            window.open('https://drive.google.com/folderview?id=1fJg1r8DNF1lXvaHM7gjS7Q-ss1TxifCE', '_blank').focus();
        }
    </script>
    @yield('js')
</body>
