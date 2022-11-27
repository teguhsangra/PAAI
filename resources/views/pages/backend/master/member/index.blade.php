@extends('layouts.app')
@section('title')
    PAAI - Member
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
                                <label style="font-weight:500"><b>Member Status</b></label><br>
                                <select id="member_status" name="member_status" class="selectpicker form-control"
                                    data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                    <option value="" selected>Select All</option>
                                    <option value="active"
                                        @if (!empty($member_status)) @if ($member_status == 'active') selected @endif
                                        @endif>Active</option>
                                    <option value="not_active"
                                        @if (!empty($member_status)) @if ($member_status == 'not_active') selected @endif
                                        @endif>Not Active</option>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Member List</h4>
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
                            <li class="nav-item">
                                <a class="nav-link  active" data-toggle="tab" href="#member_all" role="tablist">
                                    All
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#member_active" role="tablist">
                                    Active
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#member_not_active" role="tablist">
                                    Not Active
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content tab-space">
                        <div class="tab-pane material-datatables table-responsive active" id="member_all">
                            <table id="member_all-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>No. PAAI</th>
                                        <th>No. AAJI</th>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>No.Telepon</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive" id="member_active">
                            <table id="member_active-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>No. PAAI</th>
                                        <th>No. AAJI</th>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>No.Telepon</th>
                                        <th>Status</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive" id="member_not_active">
                            <table id="member_not_active-table" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>No. PAAI</th>
                                        <th>No. AAJI</th>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>No.Telepon</th>
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
    </div>
    <div class="modal fade" id="formResetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Reset Password Box</h4>
                </div>
                <div class="modal-body">
                    Are you sure, do you want to reset password for this user ?
                    <br>
                    His/Her password will same with His/Her Birthday
                    <form id="resetPassword" action="{{ url('/reset_password/member/') }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="user_id" id="user_id">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>&nbsp;
                    <button type="button" class="btn btn-primary" onclick="submitForm('resetPassword')">Yes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            $('#member_all-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}' + '?role=all',
                columns: [{
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var url_image = "";
                            if (full.picture == null) {
                                url_image = '{{ url('') }}/assets/img/logo_paai.png';
                            } else {
                                url_image = '{{ url('') }}' + full.picture;
                            }
                            var returnImage = "";
                            returnImage += '<div class="d-flex px-2 py-1">';
                            returnImage += '<div>';
                            returnImage += '<img src="' + url_image +
                                '" class="avatar avatar-sm me-3" alt="avatar image">';
                            returnImage += '</div>';
                            returnImage += '</div>';

                            return returnImage;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'aaji',
                        name: 'aaji'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {

                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var user_id = full.user_id
                            return '' +
                                '<a href="#" class="btn btn-round btn-default" title="Reset Password" onclick=resetPassword(' +
                                user_id + ')>Reset Password</a> ' +
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
            $('#member_active-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}' + '?role=active',
                columns: [{
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var url_image = "";
                            if (full.picture == null) {
                                url_image = '{{ url('') }}/assets/img/logo_paai.png';
                            } else {
                                url_image = '{{ url('') }}' + full.picture;
                            }
                            var returnImage = "";
                            returnImage += '<div class="d-flex px-2 py-1">';
                            returnImage += '<div>';
                            returnImage += '<img src="' + url_image +
                                '" class="avatar avatar-sm me-3" alt="avatar image">';
                            returnImage += '</div>';
                            returnImage += '</div>';

                            return returnImage;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'aaji',
                        name: 'aaji'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },

                    {
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var return_verified = "";

                            return_verified += '<span class="badge badge-dot me-4">';
                            return_verified += '<i class="bg-info"></i>';

                            return_verified += '<span class="text-dark text-xs">Active</span>';
                            return_verified += '</span>';
                            return return_verified;
                        }
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {

                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var user_id = full.user_id
                            return '' +
                                '<a href="#" class="btn btn-round btn-default" title="Reset Password" onclick=resetPassword(' +
                                user_id + ')>Reset Password</a> ' +
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
            $('#member_not_active-table').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                ajax: '{{ url('datatables/' . $url) }}' + '?role=not_active',
                columns: [{
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var url_image = "";
                            if (full.picture == null) {
                                url_image = '{{ url('') }}/assets/img/logo_paai.png';
                            } else {
                                url_image = '{{ url('') }}' + full.picture;
                            }
                            var returnImage = "";
                            returnImage += '<div class="d-flex px-2 py-1">';
                            returnImage += '<div>';
                            returnImage += '<img src="' + url_image +
                                '" class="avatar avatar-sm me-3" alt="avatar image">';
                            returnImage += '</div>';
                            returnImage += '</div>';

                            return returnImage;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'aaji',
                        name: 'aaji'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },

                    {
                        sortable: false,
                        "render": function(data, type, full, meta) {
                            var return_verified = "";

                            return_verified += '<span class="badge badge-dot me-4">';
                            return_verified += '<i class="bg-danger"></i>';

                            return_verified += '<span class="text-dark text-xs">Not Active</span>';
                            return_verified += '</span>';
                            return return_verified;
                        }
                    },
                    {
                        sortable: false,
                        className: "td-actions text-right",
                        "render": function(row, data, full) {

                            var url = '{{ url($url) }}';
                            var id = full.id;
                            var user_id = full.user_id
                            return '' +
                                '<a href="#" class="btn btn-round btn-default" title="Reset Password" onclick=resetPassword(' +
                                user_id + ')>Reset Password</a> ' +
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
        });

        function deleteAction(id) {
            document.deleteForm.action = "{{ url($url) }}/" + id;
            $("#deleteModal").modal();
        }

        function exportExcel() {
            var member_status = $("#member_status").val();
            var url = '{{ url('exportMember') }}' + "?member_status=" + member_status;
            var link = url;
            window.location = link;
            return false;
        }

        function resetPassword(userID) {
            document.getElementById('user_id').value = userID;
            $("#formResetPasswordModal").modal();
        }
    </script>
@endsection
