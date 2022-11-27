@extends('layouts.app')
@section('title')
    PAAI Membership Reminder
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">filter_alt</i>
                    </div>
                    <h4 class="card-title" style="font-weight:700">Filter</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label style="font-weight:500"><b>Renewal Status</b></label><br>
                                <select id="renewal_status" name="renewal_status" class="selectpicker form-control"
                                    data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                    <option value="" selected>Select All</option>
                                    <option value="on renewal"
                                        @if (!empty($renewal_status)) @if ($renewal_status == 'on renewal') selected @endif
                                        @endif>On Running</option>
                                    <option value="ready to renew"
                                        @if (!empty($renewal_status)) @if ($renewal_status == 'ready to renew') selected @endif
                                        @endif>Renewal</option>
                                    <option value="ready to terminate"
                                        @if (!empty($renewal_status)) @if ($renewal_status == 'ready to terminate') selected @endif
                                        @endif>Terminate</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group bmd-form-group">
                                <br>
                                <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="OR_table">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Booking Reminder</h4>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <ul class="nav nav-pills nav-pills-warning" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#booking_on_running" role="tablist">
                                    On Running
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#booking_renewal" role="tablist">
                                    Renewal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#booking_terminate" role="tablist">
                                    Terminate
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content tab-space">
                        <div class="tab-pane material-datatables table-responsive active" id="booking_on_running">
                            <table id="booking_on_running-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Modify</th>
                                        <th>Code</th>
                                        <th>Member</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive" id="booking_renewal">
                            <table id="booking_renewal-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Modify</th>
                                        <th>Code</th>
                                        <th>Member</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive" id="booking_terminate">
                            <table id="booking_terminate-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Modify</th>
                                        <th>Code</th>
                                        <th>Member</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="followUpModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Follow Up</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label class="control-label">Follow Up List</label>
                        <div id="follow_up_list">

                        </div>
                    </div>
                    {{ Form::open(['url' => url(''), 'method' => 'POST', 'id' => 'createForm', 'name' => 'createForm']) }}
                    <input type="hidden" name="booking_id" id="modal_booking_id">
                    <input type="hidden" name="invoice_id" value="">

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Date</label>
                        <input type="text" name="follow_up_date" class="form-control datepicker" required>
                    </div>
                    <p>Are you sure, Do you want to save this activity? ?</p>
                    {{ Form::close() }}
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                    <button type="submit" class="btn btn-success btn-link" onclick="submitForm('createForm')">Yes
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="renewOrTerminateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirmation box</h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['url' => url(''), 'method' => 'PUT', 'id' => 'updateForm', 'name' => 'updateForm']) }}
                    <p id="modalRenewMessage">Modal Message</p>
                    <br>
                    <input type="hidden" name="booking_id" id="renew_booking_id">
                    <input type="hidden" name="invoice_id" value="">
                    <input type="hidden" name="renewal_status" id="renewal_status_">

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                    <button type="submit" class="btn btn-success btn-link" onclick="submitForm('updateForm')">Yes
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var renewal_status = $("#renewal_status").val();
        $(function() {
            var tableOnRunning = $('#booking_on_running-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?renewal_status=on renewal',
                columns: [{
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var status_name = full.status_name;
                            var renewal_status = full.renewal_status;
                            var id = full.id;
                            var product_id = full.product_id;
                            var return_html = '';
                            var followUpUrl = '{{ url('getDataBookingReminder') }}';

                            var booking_url = "{{ url('bookings') }}";

                            return_html += '<button onclick=openFollowUp(' + id + ',"' +
                                followUpUrl +
                                '") class="btn btn-block btn-round btn-info" title="Detail">Follow UP</button> <br>';
                            return_html += '<button onclick=renewOrTerminateBooking(' + id +
                                ',"RN") class="btn btn-block btn-round  btn-primary" title="Edit">Ready For Renewal</button> <br>';
                            return_html += '<button onclick=renewOrTerminateBooking(' + id +
                                ',"TM") class="btn btn-block btn-round btn-danger" title="Delete">Ready For Terminate</button> <br>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'member_name',
                        name: 'member_name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var status_name = full.status_name;
                            var renewal_status = full.renewal_status;
                            var id = full.id;
                            var product_id = full.product_id;
                            var return_html = '';
                            var send_mail = '{{ url('sendReminder') }}';

                            return_html += '<button onclick=mail(' + id + ',"' + send_mail +
                                '") class="btn btn-block btn-round btn-info" >Send Reminder</button>';

                            return return_html;
                        }
                    },
                ],
                sorting: [
                    [5, 'asc']
                ]
            });
            var tableRenewal = $('#booking_renewal-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?renewal_status=ready to renew',
                columns: [{
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var status_name = full.status_name;
                            var renewal_status = full.renewal_status;
                            var id = full.id;
                            var product_id = full.product_id;
                            var return_html = '';
                            var followUpUrl = '{{ url('getDataBookingReminder') }}';
                            var booking_url = "{{ url('bookings') }}";



                            return_html += '<a href="' + booking_url + '/create?booking_id=' + id +
                                '&is_renewal=true" class="btn btn-block btn-round btn-success">Renewal</a>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'member_name',
                        name: 'member_name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    }

                ],
                sorting: [
                    [4, 'asc']
                ]
            });
            var tableTerminate = $('#booking_terminate-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?renewal_status=ready to terminate',
                columns: [{
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var status_name = full.status_name;
                            var renewal_status = full.renewal_status;
                            var id = full.id;
                            var product_id = full.product_id;
                            var return_html = '';
                            var followUpUrl = '{{ url('getDataBookingReminder') }}';
                            var booking_url = "{{ url('bookings') }}";

                            return_html += '<a onclick=deleteAction(' + id +
                                ') class="btn btn-block btn-round btn-danger" title="Delete">Terminate</button>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'member_name',
                        name: 'member_name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var status_name = full.status_name;
                            var renewal_status = full.renewal_status;
                            var id = full.id;
                            var product_id = full.product_id;
                            var return_html = '';
                            var send_mail = '{{ url('sendReminder') }}';

                            return_html += '<button onclick=mail(' + id + ',"' + send_mail +
                                '") class="btn btn-block btn-round btn-info" >Send Reminder</button>';

                            return return_html;
                        }
                    },
                ],
                sorting: [
                    [5, 'asc']
                ]
            });


        });

        function openFollowUp(bookingID, followUpUrl) {
            var url = followUpUrl + "?booking_id=" + bookingID;
            $.get(url, function(data) {
                var followUpList =
                    '<table width="100%" class="table table-striped table-bordered table-hover " id="table-renewal-reminders">' +
                    '<thead>' +
                    '<th class="text-center"></th>' +
                    '<th>Date</th>' +
                    '<th>Remarks</th>' +
                    '<th>Follow Up By</th>' +
                    '</thead>' +
                    '<tbody>';
                for (var i = 0; i < data.length; i++) {
                    followUpList += '<tr>' +
                        '<td>' + data[i]['follow_up_number'] + '</td>' +
                        '<td>' + data[i]['follow_up_date'] + '</td>' +
                        '<td>' + data[i]['remarks'] + '</td>' +
                        '<td>' + data[i]['created_by'] + '</td>' +
                        '<tr>';
                }
                followUpList += '</tbody>' +
                    '</table>';
                document.getElementById("modal_booking_id").value = bookingID;
                document.createForm.action = "{{ url($url) }}";
                document.getElementById("follow_up_list").innerHTML = followUpList;
                $("#followUpModal").modal();
            });

        }

        function renewOrTerminateBooking(bookingID, renewal_status) {
            var status = '';

            if (renewal_status == "RN") {
                status = "ready to renew";
            } else {
                status = "RT";
            }
            var message = '';
            if (renewal_status == "RN") {
                message = "Are you sure, do you want to renew this booking ?";
            } else {
                message = "Are you sure, do you want to terminate this booking ?";
            }
            document.getElementById("modalRenewMessage").innerHTML = message;
            document.getElementById("renew_booking_id").value = bookingID;
            document.getElementById("renewal_status_").value = status;
            document.updateForm.action = "{{ url($url) }}/" + bookingID;
            $("#renewOrTerminateModal").modal();
        }

        function mail(id, link) {
            document.sendMailForm.action = link;
            document.getElementById("booking_id").value = id;
            $("#sendMailModal").modal();
        }

        function exportExcel() {
            var renewal_status = $("#renewal_status").val();
            var url = '{{ url('exportBookingReminder') }}' + "?renewal_status=" + renewal_status;
            var link = url;
            window.location = link;
            return false;
        }
    </script>
@endsection
