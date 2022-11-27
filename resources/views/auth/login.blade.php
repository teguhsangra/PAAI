@extends('layouts.auth')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row vh-100">
                <div class="col-sm-4 col-12 background-login p-5">
                    <div class="row h-100">
                        <div class="col-auto col-sm-12">
                            <img src="{{ asset('assets/front_end/img/logo_alt.290055b4.png') }}" style="width: 6rem;"
                                class="mb-4" />
                        </div>
                        <div class="col col-sm-12 align-self-stretch">
                            <blockquote class="blockquote text-white h-100 mt-4" style="font-size: 0.85rem;">
                                <p class="mb-0 text-right">"My insurance policy is love letter to my darling in his difficut
                                    time". Love never fail</p>
                                <footer class="blockquote-footer text-white font-weight-bold">Esra Manurung</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-12 p-4">
                    <div class="row justify-content-end align-items-center">
                        <div class="col-auto">
                            <a href="{{ url('/') }}" class="btn btn-outline-info btn-sm">
                                Kembali ke beranda
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col py-3 pl-md-5">
                            <h2 class="font-weight-medium">
                                Masuk ke PAAI
                            </h2>
                            <p>Masukkan detail akun anda di bawah</p>

                            <div class="row">
                                <div class="col-lg-7 col-md-9 col py-3">
                                    <form method="POST" action="{{ route('login') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="font-weight-bold">Alamat Email</label>
                                            <input type="email" class="form-control" placeholder="Email" required="true"
                                                name="email" id="email">
                                            @if ($errors->has('email'))
                                                <div class="form-text" style="color: red;font-size:.875em;">
                                                    {{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label class="font-weight-bold">Kata Sandi</label>
                                            <i class="fas fa-eye-slash" id="togglePassword"></i>
                                            <input type="password" class="form-control" placeholder="Password"
                                                required="true" name="password" id="password">
                                            @if ($errors->has('password'))
                                                <div class="form-text" style="color: red;font-size:.875em;">
                                                    {{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-warning my-4 text-white">Masuk</button>
                                    </form>

                                </div>
                                <div class="col-md-8">
                                    <span class="ml-auto" style="text-align:"><a href="{{ url('forgot-password') }}"
                                            class="forgot-pass">Lupa kata sandi</a></span>
                                </div>
                            </div>
                            <div class="mb-3 align-items-center ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control control--checkbox mb-0"><span class="caption">Belum punya
                                                akun? </span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ url('register') }}" class="btn btn-outline-info btn-sm">
                                            Daftarkan diri anda
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
