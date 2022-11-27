@extends('layouts.app')
@section('title')
    PAAI - Membership
@endsection

@section('content')
    {{-- Datatables --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title" style="font-weight:700">Membership</h4>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <ul class="nav nav-pills nav-pills-warning" role="tablist">
                            <li>
                                <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                                    <i class="fa fa-plus"></i>
                                </a>
                                &nbsp;
                                &nbsp;
                            </li>
                            @foreach ($statuses as $no => $status)
                                <li class="nav-item">
                                    <a class="nav-link @if ($no == 0) active @endif" data-toggle="tab"
                                        href="#booking_{{ $status->id }}" role="tablist">
                                        {{ $status->action }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-content tab-space">
                        @foreach ($statuses as $no => $status)
                            <div class="tab-pane material-datatables table-responsive @if ($no == 0) active @endif"
                                id="booking_{{ $status->id }}">
                                <table id="booking_{{ $status->id }}-table"
                                    class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Member</th>
                                            <th>Product</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total</th>
                                            <th>Payment Status</th>
                                            <th class="disabled-sorting text-right">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- End DataTables --}}
@endsection

@section('js')
    <script>
        $(function() {
            @foreach ($statuses as $no => $status)
                $('#booking_{{ $status->id }}-table').DataTable({
                    processing: true,
                    serverSide: true,
                    "autoWidth": false,
                    ajax: '{{ url('datatables/' . $url . '?status_id=' . $status->id) }}',
                    columns: [{
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'member_name',
                            name: 'member_name'
                        },
                        {
                            data: 'product_name',
                            name: 'product_name'
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
                            data: 'total',
                            name: 'total'
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status'
                        },
                        {
                            sortable: false,
                            className: "td-actions text-right",
                            "render": function(row, data, full) {

                                var url = '{{ url($url) }}';
                                var id = full.id;
                                var payment_status = full.payment_status;
                                return '' +
                                    '<a href=' + url + '/' + id +
                                    ' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> ' +
                                    '<a href=' + url + '/' + id +
                                    '/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ' +
                                    '<a onclick="deleteAction(' + id +
                                    ')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';

                            }
                        }
                    ],
                    sorting: [
                        [0, 'asc']
                    ]
                });
            @endforeach

        })

        function deleteAction(id) {
            document.terminateForm.action = "{{ url($url) }}/" + id;
            $("#terminateModal").modal();
        }
    </script>
@endsection
