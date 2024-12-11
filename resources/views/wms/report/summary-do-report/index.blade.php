@extends('layouts.app')
@section('title')
    SUMMARY DO REPORT
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" />
    <div class="row gx-5">
        <div class="col-lg-6 col-sm-6 col-12">
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
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="category">Category</label>
                        <select id="category" class="form-select" multiple>
                            <option value="1">Sales Return</option>
                            <option value="2">Purchase Return</option>
                            <option value="3">Order</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-5">
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="id">DO No</label>
                        <select id="id" class="form-select" multiple>
                            @foreach ($outgoings as $outgoing)
                                <option value="{{ $outgoing['id'] }}">{{ $outgoing['ref_no'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="ref_no">Ref No</label>
                        <select disabled id="ref_no" class="form-select" multiple>
                            @foreach ($outgoings as $outgoing)
                                @if ($outgoing['category'] == 1)
                                    <option value="{{ $outgoing['sales_return_id'] }}">{{ $outgoing['sales_return_ref_no'] }}
                                    </option>
                                @elseif($outgoing['category'] == 2)
                                    <option value="{{ $outgoing['purchase_return_id'] }}">
                                        {{ $outgoing['purchase_return_ref_no'] }}
                                    </option>
                                @elseif($outgoing['category'] == 3)
                                    <option value="{{ $outgoing['order_id'] }}">
                                        {{ $outgoing['order_ref_no'] }}
                                    </option>
                                @endif
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
                                    <th>Created Date</th>
                                    <th>DO No</th>
                                    <th>Ref No</th>
                                    <th>Category</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Order/Return Qty</th>
                                    <th>Delivered Qty</th>
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
            $('#generate').trigger('click');
        });

        function getDateRange() {
            var datepicker = $(".datepicker-range-iso-week-numbers").data('daterangepicker');
            return {
                start_date: datepicker.startDate.format('YYYY-MM-DD'),
                end_date: datepicker.endDate.format('YYYY-MM-DD')
            };
        }

        $('#category').on('change', function () {
            let value = $(this).val();
            if(value.length > 0){
                $('#ref_no').prop('disabled', false);
            }else{
                $('#ref_no').prop('disabled', true);
            }
        });

        $('#generate').on('click', function() {
            let category = $('#category').val();
            let id = $('#id').val();
            let ref_no = $('#ref_no').val();
            let dates = getDateRange(); // Get start and end dates from the picker
            let start_date = dates.start_date;
            let end_date = dates.end_date;
            $.ajax({
                url: '{{ route('summary_do_report.generate') }}',
                type: 'GET',
                data: {
                    category: category,
                    id: id,
                    ref_no: ref_no,
                    start_date: start_date,
                    end_date: end_date
                },
                success: function(response) {
                    inventoryTable.clear().draw(); // Clear existing inventoryTable data
                    let sr_no = 1;
                    response.forEach((element1, index1) => {
                        element1.outgoing_detail.forEach((element, index) => {
                            let ref_no = '';
                            let category = '';
                            let order_qty = '';
                            if (element1.category == 1) {
                                category = 'Sales Return';
                                order_qty = element.return_qty;
                                ref_no = element1.sales_return.ref_no;
                            } else if (element1.category == 2) {
                                category = 'Purchase Return';
                                order_qty = element.qty;
                                ref_no = element1.purchase_return.grd_no;
                            } else if (element1.category == 3) {
                                category = 'Order';
                                order_qty = element.qty;
                                ref_no = element1.order.order_no;
                            }
                            let date = element1.date;
                            let [year, month, day] = date.split('-');
                            let formatteddate = `${day}-${month}-${year}`;
                            inventoryTable.row.add([
                                sr_no,
                                formatteddate,
                                element1.ref_no,
                                ref_no,
                                category,
                                element.product.part_no,
                                element.product.part_name,
                                order_qty,
                                element.qty
                            ]).draw(false);
                            sr_no++;
                        });
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
            csv.push('"SUMMARY DO REPORT"');
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
            link.setAttribute("download", "summary-do-report.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
