@extends('layouts.app')
@section('title')
    STOCK CARD REPORT
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" />
    <div class="row gx-5">
        <div class="col-lg-4 col-md-4 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc12">Date Range</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar4"></i>
                            </span>
                            <input type="text" id="abc12" class="form-control datepicker-range-iso-week-numbers">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
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
        <div class="col-lg-4 col-md-4 col-12">
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
                                    <th>Date</th>
                                    <th>Screen Name</th>
                                    <th>Ref No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Balance</th>
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
    <!-- Date Range JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/daterange.js') }}"></script>
    <script>
        let inventoryTable = $('#myTable1').DataTable({
            ordering: false,
            searching: false
        });

        $(document).ready(function() {
            $(".datepicker-range-iso-week-numbers").daterangepicker({
                drops: "up",
                opens: "left",
                showWeekNumbers: true,
                showISOWeekNumbers: true,
                startDate: moment().startOf("hour"),
                endDate: moment().startOf("hour").add(32, "hour"),
                locale: {
                    format: "DD-MM-YYYY",
                },
            });
        });

        function getDateRange() {
            var datepicker = $(".datepicker-range-iso-week-numbers").data('daterangepicker');
            return {
                start_date: datepicker.startDate.format('YYYY-MM-DD'),
                end_date: datepicker.endDate.format('YYYY-MM-DD')
            };
        }

        $('#generate').on('click', function() {
            let product = $('#part_no').val();
            if (product != null) {
                let dates = getDateRange(); // Get start and end dates from the picker
                let start_date = dates.start_date;
                let end_date = dates.end_date;
                $.ajax({
                    url: '{{ route('stock_card_report.generate') }}',
                    type: 'GET',
                    data: {
                        product: product,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function(response) {
                        inventoryTable.clear().draw();

                        let index = 1;
                        let balance = 0;

                        // Function to handle row addition and balance calculation
                        function addRow(element, type, qty, sign) {
                            qty = parseFloat(qty) || 0; // Ensure qty is a number or 0
                            balance = (sign === '+') ? balance + qty : balance - qty;

                            // Add the row to the table
                            inventoryTable.row.add([
                                index,
                                element.date,
                                type,
                                element.ref_no,
                                element.part_no,
                                element.part_name,
                                element.unit,
                                `${sign}${qty}`, // Show sign before the quantity
                                balance
                            ]);
                            index++;
                        }

                        // Loop over the sorted data from the response
                        response.forEach(element => {
                            let qty = parseFloat(element.qty) || 0; // Ensure qty is a number

                            // Determine if it's a positive or negative transaction based on type
                            let type = element
                                .type; // Assuming you added a 'type' field in sorted_data for transaction type

                            addRow(element, type, qty, element.sign);
                        });

                        // Draw the table after processing all data
                        inventoryTable.draw(false);
                    }
                });
            } else {
                alert('Part No Can`t be Empty!');
            }
        });

        $('#part_no').on('change', function() {
            let part_no = $(this).find('option:selected').data('id');
            $('#part_name').val(part_no);
        });

        function exportToExcel() {
            inventoryTable.destroy();
            const table = document.getElementById("myTable1");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"STOCK CARD REPORT"');
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
            link.setAttribute("download", "stock-card-report.csv");
            document.body.appendChild(link);
            link.click();
            let inventoryTable = $('#myTable1').DataTable({
                ordering: false,
                searching: false
            });
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
