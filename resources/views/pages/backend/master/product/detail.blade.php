@extends('layouts.app')
@section('title')
PAAI - Product {{ $product->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Product</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Product
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td>
                                   {{number_format($product->price, 0, ',', '.')}}
                                </td>
                            </tr>
                            <tr>
                                <td>Price Type</td>
                                <td>{{ $product->price_type }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
