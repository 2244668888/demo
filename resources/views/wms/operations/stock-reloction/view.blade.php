@extends('layouts.app')
@section('title')
    STOCK RELOCATION VIEW
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Ref No</label>
                        <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                            value="{{ $stock_relocation->ref_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="date" class="form-label">Created Date</label>
                        <input type="text" readonly name="date" id="date" class="form-control"
                            value="{{ $stock_relocation->date }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" disabled id="description" rows="1" class="form-control">{{ $stock_relocation->description }}</textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="transfer_by" class="form-label">Transfer By</label>
                        <input type="text" readonly name="transfer_by" id="transfer_by" class="form-control"
                            value="{{ $stock_relocation->user->user_name }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>LOCATION TRANSFER</h5>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="previous_location" class="form-label">Previous Location</label>
                        <select name="previous_location" id="previous_location" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($locations as $location)
                                <option data-area-id="{{ $location->area->id }}" data-rack-id="{{ $location->rack->id }}"
                                    data-level-id="{{ $location->level->id }}"
                                    value="{{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}"
                                    @selected($stock_relocation->previous_area_id == $location->area->id && $stock_relocation->previous_rack_id == $location->rack->id && $stock_relocation->previous_level_id == $location->level->id)>
                                    {{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="new_location" class="form-label">New Location</label>
                        <select name="new_location" id="new_location" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($locations as $location)
                                <option data-area-id="{{ $location->area->id }}" data-rack-id="{{ $location->rack->id }}"
                                    data-level-id="{{ $location->level->id }}"
                                    value="{{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}"
                                    @selected($stock_relocation->new_area_id == $location->area->id && $stock_relocation->new_rack_id == $location->rack->id && $stock_relocation->new_level_id == $location->level->id)>
                                    {{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="mainTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Unit</th>
                                <th>Available Qty</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock_relocation_details as $stock_relocation_detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock_relocation_detail->products->part_no }}</td>
                                    <td>{{ $stock_relocation_detail->products->part_name }}</td>
                                    <td>{{ $stock_relocation_detail->products->units->name ?? '' }}</td>
                                    @php
                                        $used_qty = App\Models\Location::where(
                                            'product_id',
                                            $stock_relocation_detail->product_id,
                                        )
                                            ->where('area_id', $stock_relocation_detail->previous_area_id)
                                            ->where('rack_id', $stock_relocation_detail->previous_rack_id)
                                            ->where('level_id', $stock_relocation_detail->previous_level_id)
                                            ->sum('used_qty');
                                    @endphp
                                    <td><input type="number" readonly class="form-control available_qty"
                                            name="products[{{ $loop->iteration }}][available_qty]"
                                            value="{{ $stock_relocation_detail->available_qty + $used_qty }}"><input
                                            type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                            class="product_id"
                                            value="{{ $stock_relocation_detail->product_id }}">
                                    </td>
                                    <td><input type="number" class="form-control qty"
                                            name="products[{{ $loop->iteration }}][qty]"
                                            value="{{ $stock_relocation_detail->qty }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <a type="button" class="btn btn-info" href="{{ route('stock_relocation.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="button" class="btn btn-primary submit">Update</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.card-body input, select').prop('disabled', true);
            $('#mainTable').DataTable();
        });
    </script>
@endsection
