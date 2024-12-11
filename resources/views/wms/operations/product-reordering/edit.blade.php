@extends('layouts.app')
@section('title')
    PRODUCT REORDERING EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('product_reordering.update', $product_reordering->id) }}"
            enctype="multipart/form-data" id="myForm">
            @csrf
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
                                    <td><input type="number" class="form-control" name="critical_min"
                                            value="{{ $product_reordering->critical_min }}"></td>
                                    <td><input type="number" class="form-control" name="min_qty"
                                            value="{{ $product_reordering->min_qty }}"></td>
                                    <td><input type="number" class="form-control" name="max_qty"
                                            value="{{ $product_reordering->max_qty }}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('product_reordering.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#mainTable').DataTable();
        });

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
