@extends('layouts.web')

@section('content')
<section id="section">
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header">
                <h3>Pengaduan</h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-5" />
                <div class="toolbar">
                    <ul class="nav nav-pills nav-pills-warning" role="tablist">
                        <li class="nav-item">
                            <a href="{{ url($url) }}/create" class="btn btn-warning text-white ml-2"> Buat Pengaduan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ml-2 active" data-toggle="tab" href="#booking_on_running" role="tablist">
                                Waiting
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ml-2" data-toggle="tab" href="#booking_renewal" role="tablist">
                                On Going
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ml-2" data-toggle="tab" href="#booking_terminate" role="tablist">
                                Solved
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="containter">
                            <div class="tab-content tab-space mt-2">
                                <div class="tab-pane material-datatables table-responsive active" id="booking_on_running">
                                    <table id="booking_on_running-table" class="table table-striped table-no-bordered table-hover"
                                        cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="border-top-0 font-weight-bold">Action</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">No. Tiket</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Subjek</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Tanggal</th>
                                                <th scope="col" class="border-top-0 font-weight-bold text-center">Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane material-datatables table-responsive" id="booking_renewal">
                                    <table id="booking_renewal-table" class="table table-striped table-no-bordered table-hover"
                                        cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="border-top-0 font-weight-bold">Action</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">No. Tiket</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Subjek</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Tanggal</th>
                                                <th scope="col" class="border-top-0 font-weight-bold text-center">Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane material-datatables table-responsive" id="booking_terminate">
                                    <table id="booking_terminate-table" class="table table-striped table-no-bordered table-hover"
                                        cellspacing="0" width="100%" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="border-top-0 font-weight-bold">Action</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">No. Tiket</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Subjek</th>
                                                <th scope="col" class="border-top-0 font-weight-bold">Tanggal</th>
                                                <th scope="col" class="border-top-0 font-weight-bold text-center">Status</th>
                                            </tr>
                                        </thead>
                                    </table>
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
@section('js')
<script>
    var renewal_status = $("#renewal_status").val();
        $(function() {
            var tableOnRunning = $('#booking_on_running-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?status=Waiting',
                columns: [
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var return_html = '';

                            return_html += '<a href='+url+'/'+id+' class="btn btn-block btn-round btn-info" title="Detail">View</a>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'is_closed',
                        name: 'is_closed'
                    }
                ],
                sorting: [
                    [3, 'asc']
                ]
            });
            var tableRenewal = $('#booking_renewal-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?status=On Going',
                columns: [
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var return_html = '';

                            return_html += '<a href='+url+'/'+id+' class="btn btn-block btn-round btn-info" title="Detail">View</a>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'is_closed',
                        name: 'is_closed'
                    }

                ],
                sorting: [
                    [3, 'asc']
                ]
            });
            var tableTerminate = $('#booking_terminate-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}?status=Solved',
                columns: [
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {
                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var return_html = '';

                            return_html += '<a href='+url+'/'+id+' class="btn btn-block btn-round btn-info" title="Detail">View</a>';

                            return return_html;
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'is_closed',
                        name: 'is_closed'
                    }
                ],
                sorting: [
                    [3, 'asc']
                ]
            });


        });


</script>
@endsection
