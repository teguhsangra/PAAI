@extends('layouts.web')

@section('content')
    <section>
        <div class="row">
            <div class="col-xl-6 col-lg-8 col-md-10 col-12 offset-md-1 py-3 px-4 px-md-0">
                <h3>Pengaduan</h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-4" />

                <h4 class="mb-3">Buat Pengaduan</h4>

                <div class="row">
                    <div class="col-sm col-12 mb-3">
                        <label class="font-weight-medium">Nama</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->member->name }}" disabled />
                    </div>
                    <div class="col-sm col-12 mb-3">
                        <label class="font-weight-medium">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->member->email }}" disabled />
                    </div>
                </div>
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <div class="row mb-3">
                        <div class="col">
                            <label class="font-weight-medium">Subjek</label>
                            <input name="subject" type="text" class="form-control" placeholder="Subjek"
                                required="true" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="font-weight-medium">Pesan</label>
                            <textarea class="form-control" rows="9" name="remarks"></textarea>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <label class="font-weight-medium">Lampiran</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment"/>
                                <label class="custom-file-label" for="attachment">Pilih File</label>
                            </div>

                            <div class="row" style="display: none" id="preview">
                                <div class="col">
                                    <h6>
                                        <fa-icon icon="paperclip" class="mr-1" />
                                        Tautan
                                    </h6>
                                    <div id="divImageMediaPreview">

                                    </div>
                                    <button class="btn btn-info btn-sm  p-0" type="button" onclick="removeAttachment()">
                                        <i class="fa fa-trash-alt ml-1"></i> Remove image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-auto">
                            <button class="btn btn-warning text-white" type="submit">
                                Kirim
                            </button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ url($url) }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).on('change', '#attachment', function() {


            var filesCount = $(this)[0].files.length;

            var textbox = $(this).prev();

            if (filesCount === 1) {
                var fileName = $(this).val().split('\\').pop();
                textbox.text(fileName);
            } else {
                textbox.text(filesCount + ' files selected');
            }



            if (typeof(FileReader) != "undefined") {
                $("#preview").show()
                var dvPreview = $("#divImageMediaPreview");
                dvPreview.html("");
                $($(this)[0].files).each(function() {
                    var file = $(this);
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = $("<img />");
                        img.attr("style", "width: 400px; height:400px; padding: 10px");
                        img.attr("src", e.target.result);
                        dvPreview.append(img);
                    }
                    reader.readAsDataURL(file[0]);
                });
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }


        });

        function removeAttachment(){
            var dvPreview = $("#divImageMediaPreview");
            dvPreview.html("");
            dvPreview.append('');
            $("#preview").hide()
        }
    </script>
@endsection
