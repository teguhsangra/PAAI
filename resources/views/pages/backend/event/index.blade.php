@extends('layouts.app')
@section('title')
    PAAI - Fun Friday
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Fun Friday</h4>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    <div class="material-datatables">
                        <table id="modules-table" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                            width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Start time</th>
                                    <th>End time</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            $('#modules-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('datatables/' . $url) }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var url_image = "";
                            if (full.image == null) {
                                url_image = '{{ url('') }}/not-available.jpg';
                            } else {
                                url_image = '{{ url('') }}' + full.image;
                            }
                            var returnImage = "";
                            var returnImage = returnImage +
                                '<img width="100px" height="100px" class="img-responsive" src="' +
                                url_image + '" />';

                            return returnImage;
                        }
                    },
                    {
                        data: 'start_time',
                        name: 'start_time'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {

                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var status = full.status;
                            var return_html = '';

                            return_html += '<a href=' + url + '/' + id +
                                ' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> ';
                            return_html += '<a href=' + url + '/' + id +
                                '/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                            if (status != "Done") {
                                return_html += '<a onclick="actionDone(' + id +
                                    ')" rel="tooltip"  class="btn btn-round btn-success text-white" title="Done">Done</a>';
                            }
                            return_html += '<a onclick="deleteAction(' + id +
                                ')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';

                            return return_html
                        }
                    }
                ],
                sorting: [
                    [0, 'asc']
                ]
            });
        });

        function actionDone(id) {
            document.doneForm.action = "{{ url($url) }}/update_status/" + id;
            $("#doneModal").modal();
        }

        function deleteAction(id) {
            document.deleteForm.action = "{{ url($url) }}/" + id;
            $("#deleteModal").modal();
        }
    </script>
@endsection
