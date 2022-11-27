@extends('layouts.auth')
@section('content')
<section>
    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-sm-4 col-12 background-login p-5">
                <div class="row h-100">
                    <div class="col-auto col-sm-12">
                        <img src="{{ asset('assets/front_end/img/logo_alt.290055b4.png') }}" style="width: 6rem;" class="mb-4" />
                    </div>
                    <div class="col col-sm-12 align-self-stretch">
                        <blockquote class="blockquote text-white h-100 mt-4" style="font-size: 0.85rem;">
                            <p class="mb-0 text-right">"My insurance policy is love letter to my darling in his difficut time". Love never fail</p>
                            <footer class="blockquote-footer text-white font-weight-bold">Esra Manurung</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-12 p-4">
                <div class="row justify-content-end align-items-center">
                    <div class="col-auto">
                        <a href="{{url('/')}}" class="btn btn-outline-info btn-sm" >
                            Kembali ke beranda
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col py-3 pl-md-5">
                        <h2 class="font-weight-medium">
                            Masuk ke PAAI
                        </h2>
                        <p>Masukkan email anda di bawah</p>

                        <div class="row">
                            <div class="col-lg-7 col-md-9 col py-3">
                                <form method="POST" action="{{ url('forgot-password') }}" >
                                    {{ csrf_field() }}
                                    <div class="form-group first{{ $errors->has('email') ? ' has-error' : '' }}" style="margin-bottom: 20px">
                                        <input type="text" class="form-control" placeholder="Email" name="email" id="username" required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-block btn-primary" style="height: 40px !important;margin-top:20px"> {{ __('Send Password Link') }}</button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
