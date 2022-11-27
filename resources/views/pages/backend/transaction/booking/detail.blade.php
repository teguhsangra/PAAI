@extends('layouts.app')
@section('title')
PAAI - Membership - {{ $booking->code }}
@endsection
@section('content')
<style>
    .receipt:hover {opacity: 0.7;}
    /* The Modal (background) */
    #image-viewer {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.9);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }
    .modal-content {
        animation-name: zoom;
        animation-duration: 0.6s;
    }
    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }
    #image-viewer .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }
    #image-viewer .close:hover,
    #image-viewer .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Membership</h4>
                <br>
                <a href="{{ url($url) }}"  class="btn btn-rose pull-left btn-round" style="color:#fff;font-weight:700;">
                    <i class="fa fa-arrow-left"></i> Back To Membership
                </a>

            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #385660 !important;color: #FFF;">
                                <td width="100%"><b>1. Member</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" class="table-borderless">
                                        <tr style="vertical-align: top;">
                                            <td width="50%">Member Name</td>
                                            <td width="50%">{{$booking->member->name}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td width="50%">Company Name</td>
                                            <td width="50%">{{$booking->member->company_name}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td width="50%">Nomor PAAI</td>
                                            <td width="50%">{{$booking->member->code}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td width="50%">Nomor AAJI</td>
                                            <td width="50%">{{$booking->member->aaji}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>E-mail Address</td>
                                            <td>{{$booking->member->email}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Telephone</td>
                                            <td>{{$booking->member->phone}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #385660 !important;color: #FFF;">
                                <td colspan="5">2. Detail order</td>
                            </tr>
                            <tr>
                                <td width="20%"><b>Order Code</b></td>
                                <td width="15%"><b>Product</b></td>
                                <td width="15%"><b>Mulai tanggal</b></td>
                                <td width="25%"><b>Sampai tanggal</b></td>
                                <td width="25%" ><b>Total</b></td>
                            </tr>
                            <tr>
                                <td>{{$booking->code}}</td>
                                <td>{{$booking->product->name}}</td>
                                <td>{{date("j F Y",strtotime($booking->start_date))}}</td>
                                <td>{{date("j F Y",strtotime($booking->end_date))}}</td>
                                <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                   <h4>Payment Receipt</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="other_doc_1">
                                                @if(!empty($booking->attachment))
                                                <img class="img-fluid receipt" src="{{asset($booking->attachment)}}" alt="" width="300" height="200">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($booking->status == "waiting verified")
            <div class="card-footer">
                    <a  class="col-md-6 btn-lg btn btn-danger btn-round" style="font-weight:700;color: #fff">Not Paid</a>
                    <a data-toggle="modal" data-target="#continueTransactionModal" class="col-md-6 btn-lg btn btn-success btn-round" style="font-weight:700;color: #fff">Paid</a>
            </div>
            @endif
        </div>
    </div>
</div>
<div id="image-viewer">
    <span class="close">&times;</span>
    <img class="modal-content receipt" id="full-image">
</div>
@endsection
@section('js')
<script>
    function print(link){
        var printWindow = window.open(link);
        var printAndClose = function () {
            if (printWindow.document.readyState == 'complete') {
                clearInterval(sched);
                printWindow.print();
                printWindow.close();
            }
        }
        var sched = setInterval(printAndClose, 800);
    }
    $(".other_doc_1 img").click(function(){
        $("#full-image").attr("src", $(this).attr("src"));
        $('#image-viewer').show();
    });



    $("#image-viewer .close").click(function(){
    $('#image-viewer').hide();
    });

</script>
@endsection
