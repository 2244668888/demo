@extends('layouts.app')
@section('title')
    PAYROLL VIEW
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
        .table thead tr input {
            background: transparent;
            color: white;

        }
    </style>
    <div class="card">

            <div class="card-body">
                <div class="row">
                    <h5>PAYROLL DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Payroll Year</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ $payroll->year }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Payroll Month</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ $payroll->month }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_no" class="form-label">Payroll Date</label>
                            <input type="text" readonly name="order_no" id="order_no" class="form-control"
                                value="{{ $payroll->date }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>EMPLOYEE/STAFF DETAIL</h5>
                </div>
                <br>
                {{-- <div class="row d-flex flex-row-reverse">
                    <div class="col-md-4 d-flex flex-row-reverse">
                        <button type="button" class="btn btn-warning" id="export-btn">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 datatable" id="mainTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Emp Id</th>
                                    <th>Name</th>
                                    <th>Gross Salary (RM)</th>
                                    <th>Total Deduction (RM)</th>
                                    <th>Net Salary (RM)</th>
                                    <th>Company Contribution (RM)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><input type="text" id="search-code" placeholder="Search Emp Id"></th>
                                    <th><input type="text" id="search-name" placeholder="Search Name"></th>
                                    <th><input type="text" id="search-gross_salary" placeholder="Search Gross Salary (RM)"></th>
                                    <th><input type="text" id="search-total_deduction" placeholder="Search Total Deduction (RM)"></th>
                                    <th><input type="text" id="search-net_salary" placeholder="Search Net Salary (RM)"></th>
                                    <th><input type="text" id="search-company_contribution" placeholder="Search Company Contribution (RM)"></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
<br>

                <div class="row">
                    <h5>Verification Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Approver</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payroll_verifications as $payroll_verification)
                                {{-- @dd($payroll_verification->created_by) --}}
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{  date('d-m-Y',strtotime($payroll_verification->date)) }}
                                    </td>
                                    <td>

                                        {{ $payroll_verification->created_by_user->user_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $payroll_verification->department_id->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $payroll_verification->designation_id->name ?? '' }}
                                    </td>

                                    <td>
                                       @if ($payroll_verification->status == 'Approved')
                                            <span class="badge border border-success text-success">Approved</span>
                                        @elseif ($payroll_verification->status == 'Declined')
                                            <span class="badge border border-secondary text-secondary">Declined</span>
                                        @elseif ($payroll_verification->status == 'Cancelled')
                                            <span class="badge border border-Danger text-Danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $payroll_verification->reason ?? '' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('payroll.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <script>
        var data = "{{ route('payroll_detail.payrollDetailDataView',$payroll->id) }}";
        $(document).ready(function() {
            let bool = true;
           var table =  $('.datatable').DataTable({
                perPageSelect: [5, 10, 15, ["All", -1]],
                processing: true,
                serverSide: true,
                language: {
                    processing: 'Processing' // Custom processing text
                },
                ajax: {
                    url: data, // URL for your server-side data endpoint
                    type: 'GET',
                    data: function(d) {
                        d.code = $('#search-code').val();
                        d.name = $('#search-name').val();
                        d.gross_salary = $('#search-gross_salary').val();
                        d.total_deduction = $('#search-total_deduction').val();
                        d.net_salary = $('#search-net_salary').val();
                        d.company_contribution = $('#search-company_contribution').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    },
                    {
                        data: 'user.code',
                        name: 'user.code',
                        defaultContent: ''
                    },
                    {
                        data: 'user.user_name',
                        name: 'user.user_name',
                        defaultContent: ''
                    },
                    {
                        data: 'gross_salary',
                        name: 'gross_salary',
                    },
                    {
                        data: 'total_deduction',
                        name: 'total_deduction',
                    },
                    {
                        data: 'net_salary',
                        name: 'net_salary',
                    },
                    {
                        data: 'company_contribution',
                        name: 'company_contribution',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;

            $(document).on('keyup','#dt-search-0', function() {
                console.log($(this).val())
            table.search($(this).val()).draw();
             });

        // $('.datatable thead tr').clone(true).appendTo('.datatable thead');
            $('.datatable thead tr:eq(1) th').each(function (i) {
                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });


        });

        function exportToExcel() {
            const table = document.getElementById("mainTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td,th");
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
            link.setAttribute("download", "payroll_detail.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
