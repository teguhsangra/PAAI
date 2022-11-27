@extends('layouts.auth')
@section('content')
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }
    </style>
    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2"
            style="background-image: url({{ asset('assets/front_end/img/background_login.47e93ac7.jpg') }});"></div>
        <div class="contents order-2 order-md-1">

            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7 py-5">
                        <h3>Registrasi</h3>
                        <p class="mb-4"></p>
                        <form method="POST" action="{{ url('create-user') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group first">
                                        <label for="fname">Nama Lengkap <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" placeholder="nama anda" name="username"
                                            required value="{{ old('username') }}">
                                        @if ($errors->has('username'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Nama lengkap harus
                                                di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group first">
                                        <label for="email">Email <span style="color: red">*</span></label>
                                        <input type="email" class="form-control" placeholder="email anda" name="email"
                                            required value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <div class="form-text" style="color: red;font-size:.875em;">email harus di isi
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group first">
                                        <label for="lname">Nomor telepon <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" placeholder="nomor hp anda"
                                            name="phone" required value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Nomor telepon harus
                                                di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group first">
                                        <label for="fname">Tanggal Lahir <span style="color: red">*</span></label>
                                        <input type="date" class="form-control" placeholder="tgl. lahir anda"
                                            name="birth_date" required value="{{ old('birth_date') }}">
                                        @if ($errors->has('birth_date'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Tanggal Lahir harus
                                                di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group first">
                                        <label for="lname">Nama Perusahaan Asuransi
                                        </label>
                                        <input type="text" class="form-control" placeholder="perusahaan anda"
                                            name="company_name" value="{{ old('company_name') }}">
                                        @if ($errors->has('company_name'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Nama Perusahaan
                                                Asuransi harus di isi</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group first">
                                        <label for="lname">No AAJI/AAUI/UMUM
                                        </label>
                                        <input type="text" class="form-control" placeholder="no aaji anda" name="aaji"
                                            value="{{ old('aaji') }}">
                                        @if ($errors->has('aaji'))
                                            <div class="form-text" style="color: red;font-size:.875em;">No AAJI/AAUI/UMUM
                                                harus di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group last mb-3">
                                        <label for="password">Kata sandi <span style="color: red">*</span></label>
                                        <input type="password" class="form-control" placeholder="Kata sandi" name="password"
                                            required>
                                        @if ($errors->has('password'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Kata sandi harus di
                                                isi</div>
                                        @endif
                                        <span class="form-text">(Password minimum 6 karakter)</span>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group last mb-3">
                                        <label for="re-password">Konfirmasi kata sandi <span
                                                style="color: red">*</span></label>
                                        <input type="password" class="form-control" placeholder="Konfirmasi Kata sandi"
                                            name="password_confirmation" required>
                                        @if ($errors->has('re-password_confirmation'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Kata sandi tidak
                                                sama</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group first">
                                        <label for="fname">Tanggal keanggotaan dimulai dari <span
                                                style="color: red">*</span></label>
                                        <input type="date" class="form-control"
                                            placeholder="tanggal mulai keanggotaan" name="start_date" required
                                            value="{{ old('start_date') }}">
                                        @if ($errors->has('start_date'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Tanggal
                                                keanggotaan harus di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group first">
                                        <label for="fname">Nama Pengundang
                                        </label>
                                        <input type="text" class="form-control" placeholder="nama pengundang"
                                            name="referral" value="{{ old('referral') }}">
                                        @if ($errors->has('referral'))
                                            <div class="form-text" style="color: red;font-size:.875em;">Nama Pengundang
                                                harus di isi</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>
                                        <b>Mohon di Transfer ke rek
                                            <br>
                                            BCA a/n P A A I - 5485 7999 98
                                        </b>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="lname">Upload Bukti Setor Anda
                                        <span style="color: red">*</span> </label>
                                    <input type="file" name="attachment" class="file" >
                                    <div class="input-group my-3">
                                        <input type="text" class="form-control" disabled placeholder="Upload File"
                                            id="file">
                                        <div class="input-group-append">
                                            <button type="button" class="browse btn btn-primary">Browse...</button>
                                        </div>
                                    </div>
                                    @if ($errors->has('attachment'))
                                        <div class="form-text" style="color: red;font-size:.875em;">Upload Bukti Setor
                                            harus di isi</div>
                                    @endif
                                </div>
                                <div class="ml-2 col-sm-6">
                                    <img src="" id="preview" class="img-thumbnail">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary my-4 text-white">Daftar</button>
                                </div>
                                <div class="col-md-6">
                                    <input type="button" class="btn btn-warning my-4 text-white"
                                        onclick="location.href='{{ url('/') }}';" value="Kembali ke beranda">
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on("click", ".browse", function() {
            var file = $(this).parents().find(".file");
            file.trigger("click");
        });
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $("#file").val(fileName);

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        });
    </script>
@endsection
