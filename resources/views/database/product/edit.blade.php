@extends('layouts.app')
@section('title')
    PRODUCT EDIT
@endsection
@section('content')
<style>
    .tooltip-arrow{
   display: none !important;
   width: 0px !important;
   height: 0px !important;

}
 .tooltip-inner{
   display: none !important;
   width: 0px !important;
   height: 0px !important;
}

</style>
    <div class="card">
        <form method="post" action="{{ route('product.update', $product->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="part_no">Part No.</label>
                            <input type="text" class="form-control" id="part_no" name="part_no"
                                placeholder="Enter Part No." value="{{ $product->part_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="part_name">Part Name</label>
                            <input type="text" class="form-control" id="part_name" name="part_name"
                                placeholder="Enter Part Name" value="{{ $product->part_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="type_of_product">Type of Product</label>
                            <select class="form-select" id="type_of_product" name="type_of_product">
                                <option value="" disabled selected>Select Type of Product</option>
                                @foreach ($type_of_products as $type_of_product)
                                    <option value="{{ $type_of_product->id }}" @selected($product->type_of_product == $type_of_product->id)>
                                        {{ $type_of_product->type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model"
                                placeholder="Enter Model" value="{{ $product->model }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($product->category == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="variance">Variance</label>
                            <input type="text" class="form-control" id="variance" name="variance"
                                placeholder="Enter Variance" value="{{ $product->variance }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="1" placeholder="Enter Description">{{ $product->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="moq">MOQ</label>
                            <input type="number" class="form-control" id="moq" name="moq" placeholder="Enter MOQ"
                                value="{{ $product->moq }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="unit">Unit</label>
                            <select class="form-select" id="unit" name="unit">
                                <option value="" disabled selected>Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected($product->unit == $unit->id)>{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="part_weight">Part Weight</label>
                            <input type="number" class="form-control" id="part_weight" name="part_weight"
                                placeholder="Enter Part Weight" value="{{ $product->part_weight }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="standard_packaging">Standard Packaging</label>
                            <input type="text" class="form-control" id="standard_packaging" name="standard_packaging"
                                placeholder="Enter Standard Packaging" value="{{ $product->standard_packaging }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3 d-flex">
                            <label class="form-label me-2" for="customer_supplier">Customer</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customer_supplier"
                                        name="customer_supplier" @checked($product->customer_supplier)>
                                    <label class="form-check-label" for="customer_supplier"></label>
                                </div>
                            </div>
                            <label class="form-label" for="customer_supplier">Supplier</label>
                        </div>
                    </div>
                </div>
                <div class="row customer_row @if ($product->customer_supplier) d-none @endif">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="customer_name">Customer Name</label>
                            <select class="form-select ms-2 w-100" id="customer_name" name="customer_name">
                                <option value="" disabled selected>Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected($product->customer_name == $customer->id)>
                                        {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="customer_product_code">Customer Product Code</label>
                            <input type="text" class="form-control" id="customer_product_code"
                                name="customer_product_code" placeholder="Enter Customer Product Code"
                                value="{{ $product->customer_product_code }}">
                        </div>
                    </div>
                </div>
                <div class="row supplier_row @if (!$product->customer_supplier) d-none @endif">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="supplier_name">Supplier Name</label>
                            <select class="form-select ms-2 w-100" id="supplier_name" name="supplier_name">
                                <option value="" disabled selected>Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected($product->supplier_name == $supplier->id)>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="supplier_product_code">Supplier Product Code</label>
                            <input type="text" class="form-control" id="supplier_product_code"
                                name="supplier_product_code" placeholder="Enter Supplier Product Code"
                                value="{{ $product->supplier_product_code }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="have_bom" name="have_bom"
                                value="1" @checked($product->have_bom == 1)>
                            <label class="form-check-label" for="have_bom">Have BOM</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('product.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#customer_supplier').on('change', function() {
            if ($(this).is(':checked')) {
                $('.supplier_row').removeClass('d-none');
                $('.supplier_row').find('.select2-container').addClass('w-100');
                $('.customer_row').addClass('d-none');
            } else {
                $('.supplier_row').addClass('d-none');
                $('.customer_row').removeClass('d-none');
            }
        });
    </script>
@endsection
