@extends('layouts.app')
@section('title')
    PRODUCT REORDERING VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="mainTable">
                        <thead>
                            <tr>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Unit</th>
                                <th>Type of Product</th>
                                <th>Model</th>
                                <th>Variance</th>
                                <th>Critical Min</th>
                                <th>Minimum Qty</th>
                                <th>Maximum Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $product_reordering->products->part_no }}</td>
                                <td>{{ $product_reordering->products->part_name }}</td>
                                <td>{{ $product_reordering->products->units->name ?? '' }}</td>
                                <td>{{ $product_reordering->products->type_of_products->type ?? '' }}</td>
                                <td>{{ $product_reordering->products->model }}</td>
                                <td>{{ $product_reordering->products->variance }}</td>
                                <td>{{ $product_reordering->critical_min }}</td>
                                <td>{{ $product_reordering->min_qty }}</td>
                                <td>{{ $product_reordering->max_qty }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <a type="button" class="btn btn-info" href="{{ route('product_reordering.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#mainTable').DataTable();
        });
    </script>
@endsection
