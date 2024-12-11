@extends('layouts.app')
@section('title')
    INVENTORY REPORT
@endsection
@section('content')
    <div class="row gx-5">
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0 pb-2">
                        <div class="form-group">
                            <label class="form-label" for="part_no">Part No</label>
                            <select id="part_no" class="form-select">
                                <option value="" selected disabled>Select Part No</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-id="{{ $product->part_name }}">
                                        {{ $product->part_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="part_name">Part Name</label>
                        <input type="text" readonly class="form-control" id="part_name" placeholder="Part Name">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="area">Area's</label>
                        <select id="area" class="form-select" multiple>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="rack">Rack's</label>
                        <select id="rack" class="form-select" multiple>
                            @foreach ($racks as $rack)
                                <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="level">Level's</label>
                        <select id="level" class="form-select" multiple>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-info me-2" id="generate">
                <i class="bi bi-search"></i> Search
            </button>
            <button type="button" class="btn btn-info" id="export-btn">
                <i class="bi bi-download"></i> Download
            </button>
        </div>
    </div>
    <div class="row gx-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 w-100" id="myTable1">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Qty in Stock</th>
                                    <th>Area</th>
                                    <th>Shelf</th>
                                    <th>level</th>
                                    <th>Lot No</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Datatables -->
    <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.fixedColumns.min.js') }}"></script>
    <script>
        let inventoryTable = $('#myTable1').DataTable({
            columnDefs: [{
                    width: '10%',
                    targets: 0
                },
                {
                    width: '10%',
                    targets: -1
                }
            ]
        });

        $(document).ready(function() {
            $('#generate').trigger('click');
        });

        $('#generate').on('click', function() {
            let part_no = $('#part_no').val();
            let area = $('#area').val();
            let rack = $('#rack').val();
            let level = $('#level').val();
            $.ajax({
                url: '{{ route('inventory_report.generate') }}',
                type: 'GET',
                data: {
                    product_id: part_no,
                    area_id: area,
                    rack_id: rack,
                    level_id: level
                },
                success: function(response) {
                    inventoryTable.clear().draw(); // Clear existing inventoryTable data
                    response.forEach((element, index) => {
                        if(element.used_qty != 0){
                            inventoryTable.row.add([
                                index + 1,
                                element.product.part_no,
                                element.product.part_name,
                                element.product.units.name ?? '',
                                element.used_qty,
                                element.area.name,
                                element.rack.name,
                                element.level.name,
                                element.lot_no
                            ]).draw(false);
                        }
                    });
                }
            });
        });

        $('#part_no').on('change', function() {
            let part_no = $(this).find('option:selected').data('id');
            $('#part_name').val(part_no);
        });

        function exportToExcel() {
            const table = document.getElementById("myTable1");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"INVENTORY REPORT"');
            csv.push(""); 
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td, th");
                let row = [];
                for (let j = 0; j < cells.length; j++) {
                    row.push('"' + cells[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }
            const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "inventory.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
