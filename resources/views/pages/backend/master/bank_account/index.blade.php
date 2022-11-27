@extends('layouts.app')
@section('title')
PAAI Bank Account
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Bank Account</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="material-datatables table-responsive">
                    <table id="bank_accounts-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Account No</th>
                                <th>Name</th>
                                <th>Bank</th>
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
	    $('#bank_accounts-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'account_no', name: 'account_no' },
	            { data: 'account_name', name: 'account_name' },
	            { data: 'bank_name', name: 'bank_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){

	            		var url = '{{ url($url) }}';
	            		var id = full.id;
	            		return ''+
                        '<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> '+

                        '<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> '+

                        '<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>'+

                        '';
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
	});

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection
