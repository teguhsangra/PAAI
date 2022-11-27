@php
$logo_web_black = \App\Models\WebContent::where('name', 'logo_web_black')->first();
$logo_web_white = \App\Models\WebContent::where('name', 'logo_web_white')->first();
@endphp

<nav
    class="navbar bg-transparent px-5 @if (Request::is('/')) navbar-dark @else navbar-light @endif  navbar-expand-lg">
    <div class="navbar-brand ml-lg-5 ml-3">
        <a href="/" aria-current="page" class="active">
            @if (Request::is('/'))
                <img src="{{ $logo_web_white->picture_1 }}" alt="Logo" class="w-100 img-fluid">
            @else
                <img src="{{ $logo_web_black->picture_1 }}" alt="Logo" class="w-100 img-fluid">
            @endif
        </a>
    </div>

    <button class="navbar-toggler btn btn-outline-light px-3 border-0 collapsed" type="button" data-toggle="collapse"
        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" role="img"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14">
            <path fill="currentColor"
                d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"
                class="">
            </path>
        </svg>
    </button>
    <div id="navbarSupportedContent" class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ url('/') }}" aria-current="page"
                    class="nav-link @if (Request::segment(1) == '') active @endif" target="_self">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/payment') }}" class="nav-link @if (Request::segment(1) == 'payment') active @endif"
                    target="_self">
                    Cara Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/about-us') }}" class="nav-link @if (Request::segment(1) == 'about-us') active @endif"
                    target="_self"> About Us </a>
            </li>

            @if (Auth::user())
                @if (Auth::user()->type == 'member')
                    <li class="nav-item">
                        <a href="{{ url('/funfriday') }}"
                            class="nav-link @if (Request::segment(1) == 'funfriday') active @endif" target="_self">
                            Fun Friday </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/member') }}" class="nav-link @if (Request::segment(1) == 'member') active @endif"
                            target="_self"> Member </a>
                    </li>
                    @if (Auth::user()->member->expired_date >= date('Y-m-d') || Auth::user()->member->is_verified == 'Y')
                        <li class="nav-item">
                            <a href="{{ url('/ticket') }}"
                                class="nav-link @if (Request::segment(1) == 'ticket') active @endif" target="_self">
                                Pengaduan </a>
                        </li>
                        <li class="nav-item">
                            <a onclick="gdrive()" class="nav-link" target="_self" style="cursor:pointer;"> <i
                                    class="fab fa-google-drive"
                                    @if (Request::is('/')) style="color: white"@else style="color: black" @endif></i>
                                Folder </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (Auth::user()->member->picture)
                                <img src="{{ asset(Auth::user()->member->picture) }}" class="cover rounded-circle"
                                    style="width: 28px; height: 28px;">
                            @else
                                <i class="fa fa-user-circle fa-1x"></i>
                            @endif
                        </a>
                        <ul
                            class="dropdown-menu @if (Request::is('/')) bg-dark text-white  @else bg-light text-white @endif dropdown-menu-right">
                            <li class="navbar-text text-warning d-block px-4 text-right text-nowrap">
                                {{ Auth::user()->username }} </li>
                            <!---->
                            <li role="presentation">
                                <a href="{{ url('/account') }}" class="dropdown-item" role="menuitem" target="_self">
                                    Profile </a>
                            </li>
                            @if (Auth::user()->member->expired_date >= date('Y-m-d') || Auth::user()->member->is_verified == 'Y')
                                <li role="presentation">
                                    <a href="{{ url('/account/card') }}" class="dropdown-item" role="menuitem"
                                        target="_self"> Lihat ID Card </a>
                                </li>
                                <li role="presentation">
                                    <a href="{{ url('/subscription') }}" class="dropdown-item" role="menuitem"
                                        target="_self"> Lihat Iuran </a>
                                </li>
                            @endif
                            <hr class="my-0 border-secondary">
                            <li role="presentation"><a role="menuitem" href="{{ url('logout') }}" target="_self"
                                    class="dropdown-item"> Log Out
                                </a>
                            </li>
                        </ul>

                    </li>
                @endif
            @else
                <li class="px-4">
                    <a href="/login" class="btn btn-outline-warning btn-sm my-1"> Login/Registrasi</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
