@extends('layouts.app')
@section('title')
    INVENTORY SHOPFLOOR
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                @foreach ($areas as $area)
                    <div class="col-sm-3 mb-2">
                        <div class="card" style="border: 1px solid red; border-radius: 10px;">
                            <div class="card-header" style="border-radius: 10px;">
                                <input type="hidden" value="{{ $area->area_id }}" class="area">
                                <h6 class="text-center" style="background:red; color: white; padding: 5px;">
                                    <b>{{ $area->area_name }}</b>
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                @foreach ($area->racks as $rack)
                                    <input type="hidden" value="{{ $area->area_name }} > {{ $rack->rack_name }}"
                                        class="area_rack_{{ $rack->rack_id }}">
                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor: pointer;"
                                        type="button" class="rack"
                                        data-id="{{ $rack->rack_id }}">{{ $rack->rack_name }}:-
                                        Total: {{ $rack->total_quantity ?? 0 }}, Price: {{ $rack->price ?? 0 }}</a>
                                    <br>
                                @endforeach
                            </div>
                            <div class="card-footer" style="border-radius: 10px;">
                                <h6 class="text-center" style="background:red; color: white; padding: 5px;"><b>Total:
                                        {{ $area->total_quantity ?? 0 }}</b></h6>
                                <h6 class="text-center" style="background:red; color: white; padding: 5px;"><b>Price:
                                        {{ $area->price ?? 0 }}</b></h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- LEVELS MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalTitle" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalTitle">
                        LEVELS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 w-100" id="myTable1">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Total</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                        CLOSE
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- PRODUCTS MODAL --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Title" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2Title">
                        PRODUCTS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 w-100" id="myTable2">
                            <thead>
                                <tr>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry back" data-bs-dismiss="modal">
                        BACK
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let table1;
        let table2;

        $(document).ready(function() {
            table1 = $('#myTable1').DataTable();
            table2 = $('#myTable2').DataTable();
        });

        $('.rack').on('click', function() {
            let rack_id = $(this).attr('data-id');
            let area_rack = $(this).closest('.card').find(`.area_rack_${rack_id}`).val();
            let area_id = $(this).closest('.card').find('.area').val();
            $('#exampleModalLabel').text(area_rack);

            $.ajax({
                url: '{{ route('inventory_shopfloor.generate') }}',
                type: 'GET',
                data: {
                    area_id: area_id,
                    rack_id: rack_id
                },
                success: function(response) {
                    table1.clear().draw(); // Clear the table properly

                    response.forEach(element => {
                        table1.row.add([
                            `<input type="hidden" class="area_id" value="${area_id}">
                            <input type="hidden" class="rack_id" value="${rack_id}">
                            <input type="hidden" class="level_id" value="${element.level_id}">
                            <input type="hidden" class="area_rack_level" value="${area_rack} > ${element.level_name}">
                            ${element.level_name}`,
                            element.total_quantity ?? 0,
                            element.price,
                            `<button type="button" class="btn btn-info view_product" data-bs-toggle="modal" data-bs-target="#exampleModal2">View Products</button>`
                        ]);
                    });

                    table1.draw(); // Draw the table once all rows are added
                }
            });
        });

        $(document).on('click', '.view_product', function() {
            $('#exampleModal').modal('hide');

            let area_id = $(this).closest('tr').find('.area_id').val();
            let rack_id = $(this).closest('tr').find('.rack_id').val();
            let level_id = $(this).closest('tr').find('.level_id').val();
            let area_rack_level = $(this).closest('tr').find('.area_rack_level').val();
            $('#exampleModalLabel2').text(area_rack_level);

            $.ajax({
                url: '{{ route('inventory_shopfloor.generate2') }}',
                type: 'GET',
                data: {
                    area_id: area_id,
                    rack_id: rack_id,
                    level_id: level_id
                },
                success: function(response) {
                    table2.clear().draw(); // Clear the table properly

                    response.forEach(element => {
                        table2.row.add([
                            element.product.part_no ?? '',
                            element.product.part_name ?? '',
                            element.used_qty ?? 0,
                            element.price ?? 0
                        ]);
                    });

                    table2.draw(); // Draw the table once all rows are added
                }
            });
        });

        $(document).on('click', '.back', function() {
            $('#exampleModal').modal('show');
        });
    </script>
@endsection
