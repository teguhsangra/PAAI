<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/material-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/bootstrap-fileupload.min.css') }}" rel="stylesheet" />

    @yield('css')

    <script src="https://cdn.tiny.cloud/1/dhxcodr64xti1nkdga02mhzm7h5z9pxyknajlm6bl49ucbpy/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: "lists",
            toolbar: "numlist bullist"
        });
    </script>
</head>

<body class="">
    <div class="wrapper">
        <div class="sidebar" data-color="green" data-background-color="black"
            data-image="{{ asset('assets/img/sidebar-2.jpg') }}">
            <!--
                Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

                Tip 2: you can also add an image using data-image tag
            -->
            <div class="logo">

                <a href="{{ url('') }}" class="simple-text logo-mini">
                    <img src="{{ asset('assets/front_end/img/logo_alt.290055b4.png') }}" class="logo-mini"
                        width="20" height="20" />
                </a>
                <a href="{{ url('') }}" class="simple-text logo-normal">
                    PAAI
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        @if (Auth::user()->picture == null)
                            <img src="{{ asset('assets/img/default-avatar.png') }}" />
                        @else
                            <img src="{{ asset(Auth::user()->picture) }}" />
                        @endif
                    </div>
                    <div class="user-info">
                        <a data-toggle="collapse" href="#collapseExample" class="username"
                            @if (Request::segment(1) == 'profile' ||
                                Request::segment(1) == 'notification' ||
                                Request::segment(1) == 'access_group' ||
                                Request::segment(1) == 'module' ||
                                Request::segment(1) == 'location' ||
                                Request::segment(1) == 'parameter_setting' ||
                                Request::segment(1) == 'user' ||
                                Request::segment(1) == 'company') aria-expanded="true" @endif>
                            <span>
                                {{ Auth::user()->username }}
                                <b class="caret"></b>
                            </span>
                        </a>
                        <div class="collapse @if (Request::segment(1) == 'admin/profile' ||
                            Request::segment(1) == 'notification' ||
                            Request::segment(1) == 'access_group' ||
                            Request::segment(1) == 'module' ||
                            Request::segment(1) == 'location' ||
                            Request::segment(1) == 'floor' ||
                            Request::segment(1) == 'parameter_setting' ||
                            Request::segment(1) == 'user' ||
                            Request::segment(1) == 'company') show @endif" id="collapseExample">
                            <ul class="nav">
                                <li class="nav-item @if (Request::segment(1) == 'admin/profile') active @endif">
                                    <a class="nav-link" href="{{ url('admin/profile') }}">
                                        <span class="sidebar-mini"> MP </span>
                                        <span class="sidebar-normal"> My Profile </span>
                                    </a>
                                </li>

                                @if (Auth::user()->type == 'admin')
                                    <li class="nav-item @if (Request::segment(1) == 'user') active @endif">
                                        <a class="nav-link" href="{{ url('user') }}">
                                            <span class="sidebar-mini"> U </span>
                                            <span class="sidebar-normal"> Users </span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
                    <li class="nav-item @if (Request::segment(1) == 'dashboard') active @endif">
                        <a class="nav-link" href="{{ url('dashboard') }}">
                            <i class="material-icons">dashboard</i>
                            <p> Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#master_data">
                            <i class="material-icons">apps</i>
                            <p> Master Data
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="master_data">
                            <ul class="nav">
                                <li class="nav-item @if (Request::segment(1) == 'master/bank_account') active @endif">
                                    <a class="nav-link" href="{{ url('master/bank_account') }}">
                                        <span class="sidebar-mini"> BA </span>
                                        <span class="sidebar-normal"> Bank Account </span>
                                    </a>
                                </li>
                                <li class="nav-item @if (Request::segment(1) == 'master/product') active @endif">
                                    <a class="nav-link" href="{{ url('master/product') }}">
                                        <span class="sidebar-mini"> P </span>
                                        <span class="sidebar-normal"> Product </span>
                                    </a>
                                </li>
                                <li class="nav-item @if (Request::segment(1) == 'master/merchant') active @endif">
                                    <a class="nav-link" href="{{ url('master/merchant') }}">
                                        <span class="sidebar-mini"> M </span>
                                        <span class="sidebar-normal"> Merchant </span>
                                    </a>
                                </li>
                                <li class="nav-item @if (Request::segment(1) == 'master/member') active @endif">
                                    <a class="nav-link" href="{{ url('master/member') }}">
                                        <span class="sidebar-mini"> MB </span>
                                        <span class="sidebar-normal"> Member </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item @if (Request::segment(1) == 'bookings') active @endif">
                        <a class="nav-link" href="{{ url('bookings') }}">
                            <i class="material-icons">
                                style</i>
                            <p> Membership</p>
                        </a>
                    </li>
                    <li class="nav-item @if (Request::segment(1) == 'ticketing') active @endif">
                        <a class="nav-link" href="{{ url('ticketing') }}">
                            <i class="material-icons">
                                content_paste</i>
                            <p> Ticket</p>
                        </a>
                    </li>
                    <li class="nav-item @if (Request::segment(1) == 'booking_reminder') active @endif">
                        <a class="nav-link" href="{{ url('booking_reminder') }}">
                            <i class="material-icons">
                                sports</i>
                            <p> Reminder</p>
                        </a>
                    </li>
                    <li class="nav-item @if (Request::segment(1) == 'web_content') active @endif">
                        <a class="nav-link" href="{{ url('web_content') }}">
                            <i class="material-icons">
                                space_dashboard</i>
                            <p> Web Content</p>
                        </a>
                    </li>
                    <li class="nav-item @if (Request::segment(1) == 'event') active @endif">
                        <a class="nav-link" href="{{ url('event') }}">
                            <i class="material-icons">
                                event</i>
                            <p> Fun Friday</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-minimize">
                            <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                                <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                                <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                            </button>
                        </div>
                        <a class="navbar-brand" href="#pablo">@yield('title')</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">

                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">person</i>
                                    <p class="d-lg-none d-md-block">
                                        Account
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="navbarDropdownProfile">
                                    <a class="dropdown-item" href="{{ url('') }}">Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/logout') }}">Log out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                <b> Success - </b> {{ Session::get('success') }}
                            </span>
                        </div>
                    @endif
                    @if (Session::has('warning'))
                        <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                <b> Warning - </b> {{ Session::get('warning') }}
                            </span>
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                <b> Error - </b> {{ Session::get('error') }}
                            </span>
                        </div>
                    @endif
                    @yield('content')

                    <!-- small modal -->
                    <div class="modal fade modal-mini modal-primary" id="doneModal" tabindex="-1" role="dialog"
                        aria-labelledby="doneModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                {{ Form::open(['url' => url(''), 'method' => 'POST', 'id' => 'doneForm', 'name' => 'doneForm']) }}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body">
                                    <p id="discard_or_cancel_label">Are you sure you want update status to be done?</p>
                                    <div id="discard_or_cancel_reason"></div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never
                                        mind</button>
                                    <button type="submit" class="btn btn-success btn-link">Yes
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade modal-mini modal-primary" id="deleteModal" tabindex="-1" role="dialog"
                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                {{ Form::open(['url' => url(''), 'method' => 'DELETE', 'id' => 'deleteForm', 'name' => 'deleteForm']) }}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body">
                                    <p id="discard_or_cancel_label">Are you sure you want to do delete this?</p>
                                    <div id="discard_or_cancel_reason"></div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never
                                        mind</button>
                                    <button type="submit" class="btn btn-success btn-link">Yes
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade modal-mini modal-primary" id="checkInModal" tabindex="-1" role="dialog"
                        aria-labelledby="CheckInModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                {{ Form::open(['url' => url(''), 'method' => 'PUT', 'id' => 'CheckInForm', 'name' => 'CheckInForm']) }}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to do check in this?</p>
                                    <input type="hidden" name="status_name" value="posted">
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never
                                        mind</button>
                                    <button type="submit" class="btn btn-success btn-link">Yes
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade modal-mini modal-primary" id="sendMailModal" tabindex="-1"
                        role="dialog" aria-labelledby="CheckInModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                {{ Form::open(['url' => url(''), 'method' => 'GET', 'id' => 'sendMailForm', 'name' => 'sendMailForm']) }}
                                <input type="hidden" name="booking_id" id="booking_id">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to send an mail?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never
                                        mind</button>
                                    <button type="submit" class="btn btn-success btn-link">Yes
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade modal-danger" id="errorModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body" id="error_list">
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-default btn-link"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- end small modal -->
                </div>
                <div class="modal fade modal-mini modal-primary" id="terminateModal" tabindex="-1" role="dialog"
                    aria-labelledby="terminateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-small">
                        <div class="modal-content">
                            {{ Form::open(['url' => url(''), 'method' => 'DELETE', 'id' => 'terminateForm', 'name' => 'terminateForm']) }}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body">
                                <p id="discard_or_cancel_label">Are you sure you want to do terminate this booking?
                                </p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Never
                                    mind</button>
                                <button type="submit" class="btn btn-success btn-link">Yes
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <nav class="float-left">
                        <ul>
                        </ul>
                    </nav>
                    <div class="copyright float-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="material-icons">favorite</i> by
                        <a href="http://rakitek.com/" target="_blank">Rakitek.</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap-material-design.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('assets/js/plugins/moment.min.js') }}"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"
        integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.0/fullcalendar.js"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{ asset('assets/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
    <!-- Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{ asset('assets/js/plugins/bootstrap-selectpicker.js') }}"></script>
    <!-- Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="{{ asset('assets/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="{{ asset('assets/js/plugins/bootstrap-tagsinput.js') }}"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{ asset('assets/js/plugins/jasny-bootstrap.min.js') }}"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar -->
    <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="{{ asset('assets/js/plugins/jquery-jvectormap.js') }}"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{ asset('assets/js/plugins/nouislider.min.js') }}"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{{ asset('assets/js/plugins/arrive.min.js') }}"></script>
    <!--  Google Maps Plugin    -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhd5-m9MiE3wg8bb5UpDVnd8ZKHPsusfQ"></script> -->
    <!-- Chartist JS -->
    <script src="{{ asset('assets/js/plugins/chartist.min.js') }}"></script>
    <!-- Flot Js -->
    <script src="{{ asset('assets/js/plugins/excanvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.flot.pie.js') }}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/material-dashboard.js?v=2.1.0') }}" type="text/javascript"></script>
    <!-- Custom JS-->
    <script src="{{ asset('assets/js/bootstrap-fileupload.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            //init DateTimePickers
            md.initFormExtendedDatetimepickers();
        });
        $(document).ready(function() {
            $().ready(function() {
                $sidebar = $('.sidebar');

                $sidebar_img_container = $sidebar.find('.sidebar-background');

                $full_page = $('.full-page');

                $sidebar_responsive = $('body > .navbar-collapse');

                window_width = $(window).width();

                fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
                    if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                        $('.fixed-plugin .dropdown').addClass('open');
                    }

                }

                $('.fixed-plugin a').click(function(event) {
                    // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
                    if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (window.event) {
                            window.event.cancelBubble = true;
                        }
                    }
                });

                $('.fixed-plugin .active-color span').click(function() {
                    $full_page_background = $('.full-page-background');

                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('color');

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-color', new_color);
                    }

                    if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data-color', new_color);
                    }
                });

                $('.fixed-plugin .background-color .badge').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('background-color');

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-background-color', new_color);
                    }
                });

                $('.fixed-plugin .img-holder').click(function() {
                    $full_page_background = $('.full-page-background');

                    $(this).parent('li').siblings().removeClass('active');
                    $(this).parent('li').addClass('active');


                    var new_image = $(this).find("img").attr('src');

                    if ($sidebar_img_container.length != 0 && $(
                            '.switch-sidebar-image input:checked').length != 0) {
                        $sidebar_img_container.fadeOut('fast', function() {
                            $sidebar_img_container.css('background-image', 'url("' +
                                new_image + '")');
                            $sidebar_img_container.fadeIn('fast');
                        });
                    }

                    if ($full_page_background.length != 0 && $(
                            '.switch-sidebar-image input:checked').length != 0) {
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find(
                            'img').data('src');

                        $full_page_background.fadeOut('fast', function() {
                            $full_page_background.css('background-image', 'url("' +
                                new_image_full_page + '")');
                            $full_page_background.fadeIn('fast');
                        });
                    }

                    if ($('.switch-sidebar-image input:checked').length == 0) {
                        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr(
                            'src');
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find(
                            'img').data('src');

                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                        $full_page_background.css('background-image', 'url("' +
                            new_image_full_page + '")');
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
                    }
                });

                $('.switch-sidebar-image input').change(function() {
                    $full_page_background = $('.full-page-background');

                    $input = $(this);

                    if ($input.is(':checked')) {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar_img_container.fadeIn('fast');
                            $sidebar.attr('data-image', '#');
                        }

                        if ($full_page_background.length != 0) {
                            $full_page_background.fadeIn('fast');
                            $full_page.attr('data-image', '#');
                        }

                        background_image = true;
                    } else {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar.removeAttr('data-image');
                            $sidebar_img_container.fadeOut('fast');
                        }

                        if ($full_page_background.length != 0) {
                            $full_page.removeAttr('data-image', '#');
                            $full_page_background.fadeOut('fast');
                        }

                        background_image = false;
                    }
                });

                $('.switch-sidebar-mini input').change(function() {
                    $body = $('body');

                    $input = $(this);

                    if (md.misc.sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        md.misc.sidebar_mini_active = false;

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

                    } else {

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

                        setTimeout(function() {
                            $('body').addClass('sidebar-mini');

                            md.misc.sidebar_mini_active = true;
                        }, 300);
                    }

                    // we simulate the window Resize so the charts will get updated in realtime.
                    var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 180);

                    // we stop the simulation of Window Resize after the animations are completed
                    setTimeout(function() {
                        clearInterval(simulateWindowResize);
                    }, 1000);

                });
            });
        });

        var total_request = 0;

        function submitForm(formId) {
            if (total_request > 0) {
                alert("you already make a request");
            } else {
                total_request++;
                document.getElementById(formId).submit();
            }
        }

        function changeToCurrencyFormat(textId, numberId) {
            var currentValue = parseFloat(document.getElementById(textId).value);
            if (isFloat(currentValue) || isInt(currentValue)) {
                document.getElementById(numberId).value = currentValue;
                document.getElementById(textId).value = numberWithCommas(currentValue);
            } else {
                document.getElementById(numberId).value = null;
                document.getElementById(textId).value = "You must insert number(s)";
            }
        }

        function isFloat(n) {
            return Number(n) === n && n % 1 !== 0;
        }

        function isInt(n) {
            return Number(n) === n && n % 1 === 0;
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function openNotification(notif_id, notif_url) {
            var link = "{{ url('updateNotification') }}";
            var url = link + "/" + notif_id;

            $.get(url, function(data) {
                window.location.href = notif_url
            });
        }

        function goToModule() {
            var e = document.getElementById("search_module_id");
            var module_url = e.options[e.selectedIndex].value;
            if (module_url != null) {
                window.location.href = module_url
            }
        }

        function GetFormattedDate(date) {
            var selectedDate = new Date(date);
            var month = '01';
            var date = '01';

            if ((selectedDate.getMonth() + 1) < 10) {
                month = '0' + (selectedDate.getMonth() + 1);
            }

            if (selectedDate.getDate() < 10) {
                date = '0' + selectedDate.getDate();
            }

            return month + '/' + date + '/' + selectedDate.getFullYear();
        }
    </script>
    @yield('js')
</body>

</html>
