<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('assets/front_end/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front_end/css/vendor.css') }}" rel="stylesheet" />
    <title>PAAI - Login</title>
</head>

<body>

    @yield('content')
    <div class="modal" tabindex="-1" id="modalAlert">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #ffffffdb !important;border-radius:20px !important">
                <div class="modal-header text-white bg-warning" style="border-radius:14px">

                    <h5 class="modal-title">Info</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::has('success'))
                        <p style="font-size: 18px;color:black">{!! Session::get('success') !!}

                        </p>
                    @else
                        <p style="font-size: 18px;color:black">{!! Session::get('error') !!}

                        </p>
                        <br>
                        <a href="https://wa.me/6281517189461" target="_blank" rel="noopener"
                            style="-webkit-text-size-adjust: none;
                        border-radius: 4px;
                        color: #fff;
                        display: inline-block;
                        overflow: hidden;
                        text-decoration: none;
                        background-color: #48bb78;
                        border-bottom: 8px solid #48bb78;
                        border-left: 18px solid #48bb78;
                        border-right: 18px solid #48bb78;
                        border-top: 8px solid #48bb78;">Admin
                            PAAI</a>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/bootstrap.js') }}"></script>
    <script>
        @if (Session::has('success'))
            $("#modalAlert").modal('show');
        @endif
        @if (Session::has('error'))
            $("#modalAlert").modal('show');
        @endif
        $("#togglePassword").click(function() {

            var className = $("#togglePassword").attr('class');
            className = className.indexOf('slash') !== -1 ? 'fa fa-eye' : 'fa fa-eye-slash'

            $("#togglePassword").attr('class', className);
            var input = $("#password");

            if (input.attr("type") == "text") {
                input.attr("type", "password");
            } else {
                input.attr("type", "text");
            }
        });
    </script>
    @yield('js')
</body>

</html>
