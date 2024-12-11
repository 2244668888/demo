@extends('layouts.app')
@section('title')
    SALE PRICE EDIT
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
        <form method="post" action="{{ route('sale_price.update', $saleprices->id) }}" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Part No.</label>
                            <select name="product_id" onchange="product_change()" id="product_id" class="form-select">
                                <option value="">Please Select</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected($saleprices->product_id == $product->id)>{{ $product->part_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Part Name</label>
                            <input type="text" readonly name="" id="part_name" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" readonly name="" id="model" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="variance" class="form-label">Variance</label>
                            <input type="text" readonly name="" id="variance" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="type_of_product" class="form-label">Type of Product</label>
                            <input type="text" readonly name="" id="type_of_product" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" readonly name="" id="category" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" readonly name="" id="unit" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price/Unit(RM)</label>
                            <input type="number" name="price" value="{{ $saleprices->price }}" id="price"
                                class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="effective_date" class="form-label">Effective Date:</label>
                            <input type="date" name="date" value="{{ $saleprices->date }}" id="effective_date"
                                class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12 d-none" id="remarks_div">
                        <div class="mb-3">
                            <label>Remarks:</label>
                            <p>Sale price is <b>lower or equal</b> then purchase price registered</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('sale_price.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sale price is <b>lower or equal</b> then purchase price registered</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modalNo" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="modalYes" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            flatpickr("#effective_date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($saleprices->date)->format('d-m-Y'))
            });
            product_change();
                var price = $('#price').val(); // Get the input field value
                var product_id = $('#product_id').val(); // Get the input field value
            $.ajax({
                    url: '{{ route("get.Purchase_price") }}',
                    method: 'GET',
                    data: {
                        price: price,
                        product_id: product_id
                    },
                    success: function(response) {
                        if(response.length === 0){

                        }else{
                            $('#remarks_div').removeClass('d-none');
                        }
                    }
                });

            $('#myForm').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting immediately

                var price = $('#price').val(); // Get the input field value
                var product_id = $('#product_id').val(); // Get the input field value

                $.ajax({
                    url: '{{ route("get.Purchase_price") }}',
                    method: 'GET',
                    data: {
                        price: price,
                        product_id: product_id
                    },
                    success: function(response) {
                        if(response.PurchasePrice == null){
                            $('#myForm').off('submit').submit();
                        }else{
                            $('#confirmationModal').modal('show');
                        }
                    }
                });
            });

            // When 'Yes' button is clicked inside the modal
            $('#modalYes').on('click', function() {
                $('#confirmationModal').modal('hide'); // Close the modal
                $('#myForm').off('submit').submit(); // Submit the form
            });

            // When 'No' button is clicked inside the modal
            $('#modalNo').on('click', function() {
                $('#confirmationModal').modal('hide'); // Close the modal
            });
        });

        var products = {!! json_encode($products) !!};

       
        function product_change() {
            var productId = $("#product_id").val();

            $.ajax({
                url: "{{ route('sale_price.getData')}}", 
                type: 'GET',
                dataType: 'json',
                data: {
                        product_id: productId 
                    },
                success: function(response) {
                    if(response.purchase_price.product){
                var product = response.purchase_price.product;
                $('#part_name').val(product.part_name);
                $('#model').val(product.model);
                $('#variance').val(product.variance);
                $('#type_of_product').val(product.type_of_products?.type);
                $('#category').val(product.categories?.name);
                $('#unit').val(product.units != null ? product.units?.name : '');
                // $('#price').val(response.purchase_price.price);
                lastPrice = response.purchase_price.price;

                    }else{
                        var product = response.purchase_price;
                $('#part_name').val(product.part_name);
                $('#model').val(product.model);
                $('#variance').val(product.variance);
                $('#type_of_product').val(product.type_of_products?.type);
                $('#category').val(product.categories?.name);
                $('#unit').val(product.units != null ? product.units.name : '');
                    }
                    console.log('Data received:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        };
    </script>
@endsection
