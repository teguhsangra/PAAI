@extends('layouts.app')
@section('title')
Rakomsis Ticketing
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Ticketing</h4>
            </div>
            <div class="card-body">

                <div class="material-datatables table-responsive">
                    <table id="ticketing-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th class="disabled-sorting">Actions</th>
                                <th>Created At</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Status</th>
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
	    $('#ticketing-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            {
	            	sortable:false,
                    className: "td-actions ",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status = full.status;
	            		var id = full.id;
                        var return_html = '';

                        return_html += '<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-small btn-info text-white" title="Detail"><i class="fa fa-search"></i> Reply</a> <br><br>';
                        if(status != 'Solved'){
                            return_html += '<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-small btn-success text-white"><i class="fa fa-check"></i> Solved</a>';
                        }

                        return return_html;
	            	}
	            },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'user', name: 'user' },
	            { data: 'subject', name: 'subject' },
	            { data: 'is_closed', name: 'is_closed' }
	        ],
	        sorting:[[ 1, 'desc' ]]
	    });
	});

    function deleteAction(id){

        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection
