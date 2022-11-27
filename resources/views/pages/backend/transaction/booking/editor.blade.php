@extends('layouts.app')

@section('title')
    PAAI - Membership - Editor
@endsection

@section('content')

    {{ Form::open(['url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data']) }}
    <input type="hidden" name="status_name" id="status_name">
    <input type="hidden" name="booking_id" @if (!empty($booking)) value="{{ $booking->id }}" @endif>
    <input type="hidden" name="is_renewal" id="is_renewal"
        @if (!empty($is_renewal)) value="{{ $is_renewal }}" @endif
        @if (!empty($booking)) value="{{ $booking->is_renewal }}" @endif>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-radius:35px">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">library_books</i>
                    </div>
                    <h4 class="card-title" style="font-weight: 700">
                        Member Form
                        <a href="{{ url($url) }}" class="btn btn-rose pull-right btn-round"
                            style="color:#fff;font-weight:700;">
                            <i class="fa fa-arrow-left"></i> Back To Membership
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row" id="customer_selector">
                        <label class="col-sm-2 col-form-label">Member</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('customer_name') ? ' has-error' : '' }}"
                                id="new_customer" style="display:none;">
                                <div class="input-group mb-3">
                                    <input type="text" id="new_customer_name" class="form-control"
                                        style="height:42px !important; margin-top:5px !important" readonly>
                                    <div class="input-group-append">
                                        <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal"
                                            data-target="#customerModel"><i class="material-icons">edit</i> Edit Member</a>
                                    </div>
                                </div>
                                <label class="error">{{ $errors->first('customer_name') }}</label>
                            </div>
                            <div class="form-group bmd-form-group{{ $errors->has('member_id') ? ' has-error' : '' }}"
                                id="exist_customer">
                                @if (!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{ $booking->member->name }}"
                                        readonly>
                                    <input type="hidden" name="member_id" id="member_id" value="{{ $booking->member_id }}">
                                @else
                                    <div class="input-group mb-3">
                                        <select class="selectpicker form-control col-md-10" id="customer_id"
                                            name="member_id" data-size="5" data-style="select-with-transition"
                                            data-show-subtext="true" data-live-search="true">
                                            <option value="" disabled selected>Select Your Option</option>
                                            @foreach ($members as $detail)
                                                @php
                                                    $selected = '';
                                                    if (!empty($booking)) {
                                                        if ($booking->member_id == $detail->id) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option value="{{ $detail->id }}" {{ $selected }}>
                                                    {{ $detail->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal"
                                                data-target="#customerModel"><i class="material-icons">add</i> Create
                                                Member</a>
                                        </div>
                                    </div>
                                @endif
                                <label class="error">{{ $errors->first('member_id') }}</label>
                            </div>

                            <div class="modal fade" id="customerModel" tabindex="-1" role="dialog"
                                aria-labelledby="customerModelLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Create New Member</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                <i class="material-icons">clear</i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Name</label>
                                                        <input type="text" class="form-control" name="member_name"
                                                            id="member_name">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Email</label>
                                                        <input type="text" class="form-control" name="member_email"
                                                            id="member_email">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Phone</label>
                                                        <input type="text" class="form-control" name="member_phone"
                                                            id="member_phone">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Company Name</label>
                                                        <input type="text" class="form-control"
                                                            name="member_company_name" id="member_company_name">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">No. AAJI/AAUI/UMUM</label>
                                                        <input type="text" class="form-control" name="member_aaji"
                                                            id="member_aaji">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Birth date</label>
                                                        <input type="text" name="member_birth_date"
                                                            id="member_birth_date"
                                                            class="form-control datepicker text-center"
                                                            placeholder="Birth Date">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-link pull-left"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" onclick="setCustomer()"
                                                class="btn btn-success btn-link pull-right">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="customer_status" name="customer_status" value="E">
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-radius:35px">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">library_books</i>
                    </div>
                    <h4 class="card-title" style="font-weight: 700">
                        Membership Form
                    </h4>
                </div>
                <div class="card-body">
                    <input type="hidden" name="start_date_counted" id="start_date_counted" value="Y">
                    <div class="row" id="start_to_end">
                        <label class="col-sm-2 col-form-label">Periode</label>
                        <div class="col-sm-10">
                            <div class="row" id="datepicker">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="start_date" id="start_date"
                                            class="form-control datepicker text-center" placeholder="Start Date"
                                            @if (!empty($booking) && empty(Request::get('is_renewal'))) value="{{ date('m/d/Y', strtotime($booking->start_date)) }}"
                                        @elseif(!empty($booking) && !empty(Request::get('is_renewal'))) value="{{ date('m/d/Y', strtotime($booking->end_date . '+1 days')) }}"
                                        @else value="{{ date('m/d/Y') }}" @endif>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="end_date" id="end_date"
                                            class="form-control datepicker text-center" placeholder="End Date"
                                            @if (!empty($booking)) value="{{ date('m/d/Y', strtotime($booking->end_date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Term Notice Period</label>
                        <div class="col-sm-10">
                            <div
                                class="form-group bmd-form-group{{ $errors->has('term_notice_period') ? ' has-error' : '' }}">
                                <input type="number" class="form-control text-center" name="term_notice_period"
                                    id="term_notice_period" min="0" placeholder="Free term of payment"
                                    @if (!empty($booking)) value="{{ $booking->term_notice_period }}"  @else value="0" @endif>
                                <label class="error">{{ $errors->first('term_notice_period') }}</label>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-radius:35px">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">library_books</i>
                    </div>
                    <h4 class="card-title" style="font-weight: 700">
                        Detail Transaction
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th width="50%" style="font-weight:700;">Description</th>
                                            <th width="50%" style="font-weight:700;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $product = DB::table('products')
                                                ->where('id', 1)
                                                ->first();
                                        @endphp
                                        <input type="hidden" name="product_id" id="product_id"
                                            value="{{ $product->id }}">
                                        <tr>
                                            <td class="text-center">{{ $product->name }}
                                                <br>
                                                <input type="text" class="form-control text-center"
                                                    id="format_detail_price"
                                                    @if (!empty($booking)) value="{{ number_format($booking->total, 0, ',', '.') }}" @else  value="{{ number_format($product->price, 0, ',', '.') }}" @endif
                                                    onchange="changeToCurrencyFormat('format_detail_price', 'detail_price');countPrice();">
                                                <input type="hidden" id="detail_price"
                                                    @if (!empty($booking)) value="{{ $booking->total }}" @else value="{{ $product->price }}" @endif>';

                                            </td>
                                            <td id="view_total_price" class="text-right"
                                                style="color:black;font-weight:400;"></td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td colspan="1" style="color:black;font-weight:400;">
                                                Grand Total
                                                <input type="hidden" name="total" id="total"
                                                    @if (!empty($booking)) value="{{ $booking->total }}" @else value="{{ $product->price }}" @endif>
                                            </td>
                                            <td id="view_sub_total" class="text-right"
                                                style="color:black;font-weight:400;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Payment Photo(s) <br> <small>You can upload with payment
                                receipt</small></label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3 text-center">
                                    <label class="form-label">Payment Receipt</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload"
                                        style="width:150px;">
                                        <div class="fileupload-new thumbnail">
                                            @if (!empty($booking) && empty(Request::get('is_renewal')))
                                                <img src="{{ asset($booking->attachment) }}" alt="attachment"
                                                    width="150">
                                            @elseif(!empty($booking) && !empty(Request::get('is_renewal')))
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}"
                                                    alt="attachment" width="150">
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}"
                                                    alt="attachment" width="150">
                                            @endif

                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail"
                                            style="width:150px;height:150px;"></div>
                                        <div>
                                            <span class="btn btn-file btn-primary"><span class="fileupload-new"><i
                                                        class="fa fa-picture-o"></i> Select image</span><span
                                                    class="fileupload-exists"><i class="fa fa-picture-o"></i>
                                                    Change</span>
                                                <input type="file" id="attachment" name="attachment"
                                                    @if (!empty($booking) && empty(Request::get('is_renewal'))) value="{{ $booking->attachment }}"
                                                @elseif(!empty($booking) && !empty(Request::get('is_renewal')))
                                                value=""
                                                @else
                                                value="" @endif />
                                                <input type="hidden" id="attachment_update">

                                            </span>
                                            <a href="#" class="btn fileupload-exists btn-danger"
                                                data-dismiss="fileupload">
                                                <i class="fa fa-times"></i> Remove
                                            </a>
                                        </div>

                                    </div>
                                    <label class="error">{{ $errors->first('attachment') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @if (!empty(Request::get('action_status')))
                        <a onclick="continueTransaction('complete')" class="col-md-12 btn-lg btn btn-success">Complete</a>
                    @else
                        <a onclick="continueTransaction('open')" class="col-md-3 col-sm-offset-3 btn-lg btn btn-info">Save
                            To Draft</a>

                        <a onclick="continueTransaction('posted')"
                            class="col-md-4 col-sm-offset-1 btn-lg btn btn-default">Posting</a>
                    @endif


                    <div class="modal fade modal-mini modal-primary" id="continueTransactionModal" tabindex="-1"
                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-small">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="material-icons">clear</i></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p id="modal_label">Are you sure you want to do continue ?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                    <button type="button" class="btn btn-success btn-link"
                                        onclick="submitForm('{{ $form_id }}')">Yes
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}

@endsection

@section('js')
    <script>
        var selected_room = new Array;
        var new_item = new Array;
        countPrice()



        $(document).on('dp.change', 'input[name=start_date]', function() {
            onPeriodeChanged('start_date');
        });



        function onPeriodeChanged(driven_by, others = '', date_format = '') {
            var start_date_counted = document.getElementById("start_date_counted").value;
            var start_date = document.getElementById("start_date" + others).value;
            var length_of_term = 12;
            var end_date = document.getElementById("end_date" + others).value;
            var link = "{{ url('setup_periode') }}";
            var url = link + "?driven_by=" + driven_by + "&start_date=" + start_date + "&length_of_term=" + length_of_term +
                "&end_date=" + end_date + "&start_date_counted=" + start_date_counted + "&date_format=" + date_format;

            $.get(url, function(data) {
                if (data['message'] == 'complete') {
                    document.getElementById("start_date" + others).value = data['start_date'];
                    document.getElementById("end_date" + others).value = data['end_date'];
                }
            });

        }

        function setCustomer() {
            var customer_name = $("input[name='member_name']").val();
            if (customer_name == '') {
                alert('Member name are required');
            } else {
                $("input[name='customer_status']").val("N");
                $('#new_customer_name').val(customer_name);
                $('#new_customer').show();
                $('#exist_customer').hide();
                $('#customerModel').modal('hide');
            }
        }


        function continueTransaction(status_name) {

            if (status_name == "open") {
                document.getElementById("status_name").value = status_name;
                document.getElementById("modal_label").innerHTML =
                    "You are going to save to draft this form, and you can edit this form further. <br> Are you sure want to continue ?";
            } else if (status_name == "posted") {
                document.getElementById("status_name").value = status_name;
                document.getElementById("modal_label").innerHTML =
                    "You are going to posting this form, and you can't edit this form anymore. <br> Are you sure want to continue ?";
            } else if (status_name == "complete") {
                document.getElementById("status_name").value = status_name;
                document.getElementById("modal_label").innerHTML =
                    "You are going to complete this form. <br> Are you sure want to continue ?";
            } else {

            }

            var error_list = "";
            var customer_status = document.getElementById("customer_status").value;
            var customer_id = document.getElementById("customer_id").value;
            var start_date = document.getElementById("start_date").value;
            var end_date = document.getElementById("end_date").value;
            var attachment = document.getElementById("attachment").value;
            console.log(attachment)
            if (customer_status == "E") {
                if (customer_id == "") {
                    error_list += '<div class="alert alert-warning">' +
                        '<span><b> Sorry !!! You have to select Member</b> </span>' +
                        '</div>';
                }
            }
            if (start_date == "" || end_date == "") {
                error_list += '<div class="alert alert-warning">' +
                    '<span><b> Sorry !!! You have to put start date  and end date</b> </span>' +
                    '</div>';
            }

            if (attachment == "" || attachment == null) {
                error_list += '<div class="alert alert-warning">' +
                    '<span><b> Sorry !!! You have to put payment receipt</b> </span>' +
                    '</div>';
            }

            if (error_list != "") {
                document.getElementById("error_list").innerHTML = error_list;

                $('#continueTransactionModal').modal('hide');
                $("#errorModal").modal();

            } else {
                $("#continueTransactionModal").modal();
            }
        }

        function isEmpty(obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key))
                    return false;
            }
            return true;
        }

        function countPrice() {
            var detail_price = parseFloat(document.getElementById("detail_price").value);
            var length_of_term = 1;
            var total_price = 0;
            var sub_total = 0;

            total_price = detail_price * length_of_term;
            sub_total = total_price;

            document.getElementById("total").value = sub_total;
            document.getElementById("view_total_price").innerHTML = numberWithCommas(parseInt(sub_total));
            document.getElementById("view_sub_total").innerHTML = numberWithCommas(parseInt(sub_total));
        }
    </script>
@endsection
