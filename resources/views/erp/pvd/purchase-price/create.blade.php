@extends('layouts.app')
@section('title')
    PURCHASE PRICE CREATE
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
        <form method="post" action="{{ route('purchase_price.store') }}" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>PURCHASING PRICE DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Part No.</label>
                            <select name="product_id" onchange="product_change()" id="product_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->part_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Part Name</label>
                            <input type="text" readonly id="part_name" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="col-md-5 col-lg-4">
                            <label for="" class="form-label">Model</label>
                        </div>
                        <div class="mb-3">
                            <input type="text" readonly id="model" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="col-md-5 col-lg-4">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Variance:</label>
                            <input type="text" readonly id="variance" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Type of Product:</label>
                            <input type="text" readonly id="type_of_product" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Category:</label>
                            <input type="text" readonly id="category" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Unit :</label>
                            <input type="text" readonly id="unit" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Price/Unit (RM) :</label>
                            <input type="text" name="price" id="price" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Date :</label>
                            <input type="date" placeholder="" name="date" id="date"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('purchase_price.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-save">Submit</button>
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
                    <p>Purchase price is <b>higher or equal</b> then sale price registered</p>
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
        var products = {!! json_encode($products) !!};

        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
            });
            $('#myForm').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting immediately

                var price = $('#price').val(); // Get the input field value
                var product_id = $('#product_id').val(); // Get the input field value

                $.ajax({
                    url: '{{ route("get.Sale_price") }}',
                    method: 'GET',
                    data: {
                        price: price,
                        product_id: product_id
                    },
                    success: function(response) {
                        if(response.SalePrice == null){
                            console.log(response);
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
                $('#price').val('');
                $('#confirmationModal').modal('hide'); // Close the modal
            });
        });
        var lastPrice;
        function product_change() {
            var productId = $("#product_id").val();

            $.ajax({
                url: "{{ route('purchase_price.getData')}}", 
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
                $('#unit').val(product.units != null ? product.units?.name : '');
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
