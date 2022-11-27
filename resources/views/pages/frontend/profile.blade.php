@extends('layouts.web')

@section('content')
    <div class="container mt-5 mb-5">
        @if ($member->is_verified == 'N')
            <div class="alert alert-info" role="alert">
                Maaf Akun anda sedang di verifikasi
            </div>
        @endif

        {{ Form::open(['url' => $form_url, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
        <div class="card">
            <div class="card-header">
                <h3>Profile</h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-5" />
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="p-3 h-100">
                            <div class="text-center mb-4">
                                @if ($member->picture != null)
                                    <img src="{{ asset($member->picture) }}" class="rounded-circle cover"
                                        style="width: 160px; height: 160px;" />
                                @else
                                    <i class="fa fa-user-circle fa-10x"></i>
                                @endif
                            </div>
                            <input type="file" class="form-control" name="picture" accept="image/*" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 h-100">
                            <div class="row">
                                <div class="col">
                                    <h4 class="font-weight-medium mb-1">Biodata</h4>
                                    <hr class="border-warning w-50 d-inline-block mt-0 mb-4" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Nama Lengkap</label>
                                </div>
                                <div class="col-7">
                                    <input name="name" type="text" class="form-control" required="true"
                                        value="{{ $member->name }}" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Tanggal Lahir</label>
                                </div>
                                <div class="col-7">
                                    <input type="date" name="birth_date" required value="{{ $member->birth_date }}">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Nomor Telepon</label>
                                </div>
                                <div class="col-7">
                                    <input name="phone" type="text" class="form-control" required="true"
                                        value="{{ $member->phone }}" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Perusahaan</label>
                                </div>
                                <div class="col-7">
                                    <input name="company_name" type="text" class="form-control" required="true"
                                        value="{{ $member->company_name }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 h-100">
                            <div class="row justify-content-end">
                                <div class="col">
                                    <h4 class="font-weight-medium mb-1">Informasi Agen Asuransi</h4>
                                    <hr class="border-warning w-50 d-inline-block mt-0 mb-4" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Nomor AAJI</label>
                                </div>
                                <div class="col-7">
                                    <input name="aaji" type="text" class="form-control" value="{{ $member->aaji }}" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Nomor PAAI</label>
                                </div>
                                <div class="col-7">
                                    <label v-if="profile.member.memberVerified"
                                        class="form-control-plaintext">{{ $member->code }}</label>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Berlaku Hingga</label>
                                </div>
                                <div class="col-7">
                                    <label v-if="profile.member.memberVerified"
                                        class="form-control-plaintext">{{ date('j F Y', strtotime($member->expired_date)) }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3">
                            <div class="row">
                                <div class="col">
                                    <h4 class="font-weight-medium mb-1">Akses Pengguna</h4>
                                    <hr class="border-warning w-50 d-inline-block mt-0 mb-4" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Email</label>
                                </div>
                                <div class="col-7">
                                    <input name="email" type="email" class="form-control" required="true"
                                        value="{{ $member->email }}" />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 align-self-center">
                                    <label class="mb-0 font-weight-medium">Password Baru</label>
                                </div>
                                <div class="col-7">
                                    <input name="password" type="password" class="form-control" />
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3">
                            <div class="row">
                                <div class="col">
                                    <h4 class="font-weight-medium mb-1">Media Sosial</h4>
                                    <hr class="border-warning w-50 d-inline-block mt-0 mb-4" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="font-weight-medium">Facebook</label>
                                    <input name="url_facebook" type="text" class="form-control"
                                        value="{{ $member->url_facebook }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="font-weight-medium">Instagram</label>
                                    <input name="url_instagram" type="text" class="form-control"
                                        value="{{ $member->url_instagram }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="font-weight-medium">Twitter</label>
                                    <input name="url_twitter" type="text" class="form-control"
                                        value="{{ $member->url_twitter }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="font-weight-medium">Youtube</label>
                                    <input name="url_youtube" type="text" class="form-control"
                                        value="{{ $member->url_youtube }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mb-5">
                    <div class="col-auto">
                        <button class="btn btn-warning text-white" type="submit">
                            Update
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ url('/') }}" class="btn btn-light">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
