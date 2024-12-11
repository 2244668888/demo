@extends('layouts.app')
@section('title')
    INVENTORY DASHBOARD
@endsection
@section('content')
    <style>
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-item span {
            margin-left: 5px;
        }

        .legend-badge {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-success {
            background-color: lightgreen;
        }

        .badge-warning {
            background-color: yellow;
        }

        .badge-danger {
            background-color: red;
        }

        .badge-secondary {
            background-color: grey;
        }

        .shadow-custom {
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="card shadow-custom">
                    <div class="card-body">
                        <h5>LEGEND</h5>
                        <div class="legend-item">
                            <div class="legend-badge badge-success"></div>
                            <span>Available Quantity > Minimum Qty</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-badge badge-warning"></div>
                            <span>Critical Min Qty < Available Quantity < Minimum Qty</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-badge badge-danger"></div>
                            <span>Available Quantity < Critical Min Qty</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-badge badge-secondary"></div>
                            <span>Not Set</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Critical Min Qty</th>
                            <th>Minimum Qty</th>
                            <th>Maximum Qty</th>
                            <th>Available Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $availableQty = $product->location->used_qty ?? 0;
                                $oneDBuffer = $product->reordering->critical_min ?? null;
                                $threeDBuffer = $product->reordering->min_qty ?? null;
                                $max_qty = $product->reordering->max_qty ?? null;
                                $status = '';

                                if (is_null($oneDBuffer) && is_null($threeDBuffer)) {
                                    $status = 'badge-secondary';
                                } elseif ($availableQty > $threeDBuffer) {
                                    $status = 'badge-success';
                                } elseif ($availableQty < $threeDBuffer && $availableQty > $oneDBuffer) {
                                    $status = 'badge-warning';
                                } elseif ($availableQty < $oneDBuffer) {
                                    $status = 'badge-danger';
                                }
                            @endphp
                            <tr>
                                <td>{{ $product->part_no }}</td>
                                <td>{{ $product->part_name }}</td>
                                <td>{{ $oneDBuffer ?? '' }}</td>
                                <td>{{ $threeDBuffer ?? '' }}</td>
                                <td>{{ $max_qty ?? '' }}</td>
                                <td>{{ $availableQty }}</td>
                                <td>
                                    <div class="legend-badge {{ $status }}"></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
