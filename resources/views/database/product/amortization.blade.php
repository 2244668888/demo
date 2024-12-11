@extends('layouts.app')
@section('title')
    PRODUCT AMORTIZATION
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
        <form method="post" action="{{ route('product.amortization.update', $amortization->id ?? 0) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Product/Asset Information</h5>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="amortization_qty">Amortization Quantity</label>
                            <input type="number" class="form-control" id="amortization_qty" name="amortization_qty"
                                placeholder="Enter Amortization Quantity" value="{{ $amortization->amortization_qty ?? '' }}">
                            <input type="hidden" class="form-control" name="product_id"
                                placeholder="Enter Amortization Quantity" value="{{ $id }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="delivered_qty">Delivered Quantity</label>
                            <input type="number" class="form-control" id="delivered_qty" name="delivered_qty"
                                placeholder="Enter Delivered Quantity" value="{{ $amortization->delivered_qty ?? '' }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="opening_delivered_qty">Opening Delivered Quantity</label>
                            <input type="number" class="form-control" id="opening_delivered_qty" name="opening_delivered_qty"
                                placeholder="Enter Opening Delivered Quantity" value="{{ $amortization->opening_delivered_qty ?? '' }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="balance_amortization">Balance Amortization</label>
                            <input type="text" class="form-control" readonly id="balance_amortization" name="balance_amortization"
                                value="{{ $amortization->balance_amortization ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Additional Information (Optional)</h5>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="month" class="form-control" id="numberInput" name="start_date"
                                value="{{ $amortization->start_date ?? '' }}" id="numberInput">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="amortization_period">Amortization Period</label>
                            <input type="text" class="form-control" id="amortization_period" name="amortization_period"
                                placeholder="Enter Amortization Period" value="{{ $amortization->amortization_period ?? '' }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            @php
                                $unit_id = $amortization->unit_id ?? '';
                            @endphp
                            <label class="form-label" for="unit">Unit</label>
                            <select class="form-select" id="unit" name="unit">
                                <option value="" disabled selected>Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected($unit_id == $unit->id)>{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('product.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function update_balance(){
            var amortization_qty = $('#amortization_qty').val();
            var delivered_qty = $('#delivered_qty').val();
            var opening_delivered_qty = $('#opening_delivered_qty').val();
            var balance = 0;
            balance = Number(amortization_qty) - Number(opening_delivered_qty) - Number(delivered_qty);
            $('#balance_amortization').val(balance);
        }

        $('#amortization_qty, #delivered_qty, #opening_delivered_qty').on('input', function() {
            update_balance();
        });

    </script>
@endsection
