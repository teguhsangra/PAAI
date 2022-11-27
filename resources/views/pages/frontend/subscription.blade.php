@extends('layouts.web')

@section('content')
<section id="section">
   <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header">
                <h3>Pembayaran Iuran</h3>
                <hr class="border-warning w-25 d-inline-block mt-0 mb-5" />
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped table-no-bordered table-hover" style="width:100%" id="subscription-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-top-0 font-weight-bold">#</th>
                                    <th scope="col" class="border-top-0 font-weight-bold">Mulai tanggal</th>
                                    <th scope="col" class="border-top-0 font-weight-bold">Sampai tanggal</th>
                                    <th scope="col" class="border-top-0 font-weight-bold">Jumlah Pembayaran</th>
                                    <th scope="col" class="border-top-0 font-weight-bold text-center">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>
</section>
@endsection
@section('js')
<script>
    $(function() {
        $('#subscription-table').DataTable({
	        processing: true,
	        serverSide: true,
            "autoWidth": false,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'start_date', name: 'start_date' },
	            { data: 'end_date', name: 'end_date' },
                { data: 'total', name: 'total' },
                {
                    sortable: false,
                    "render" : function(data, type, full, meta){
                        var status = full.payment_status;
                        var btn = "";
                        if(status == 'paid')
                        {
                            btn += ' <span v-if="payment.status" class="badge badge-pill border text-success border-success">'+status+'</span>';

                        }

                        return btn;
                    }
                },

	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
    });
</script>
@endsection
