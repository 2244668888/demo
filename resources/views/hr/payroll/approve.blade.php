@extends('layouts.app')
@section('title')
    PAYROLL APPROVAL
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
                            <input type="text" name="order_no" id="order_no" class="form-control"
                                value="{{ $payroll->date }}" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>EMPLOYEE/STAFF DETAIL</h5>
                </div>
                <br>
                <div class="row d-flex flex-row-reverse">
                    <div class="col-md-4 d-flex flex-row-reverse">
                        <button type="button" class="btn btn-warning" id="export-btn">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
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
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6 d-flex gap-2 justify-content-start">
                            <a type="button" class="btn btn-info" href="{{ route('payroll.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="col-md-6 d-flex gap-1 justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#Modalverify" class="btn btn-success float-end mx-2" >Verify</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline" class="btn btn-warning float-end mx-2" >Decline</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#Modalcancel" class="btn btn-danger float-end mx-2" >Cancel</button>
                        </div>
                    </div>
                </div>
            </div>




    <!-- Modal verify -->
    <div class="modal modal-lg fade" id="Modalverify" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Verification (Verify)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use App\Models\Department;
                                use App\Models\Designation;

                                $department = Department::find(Auth::user()->department_id);
                                $designation = Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{date('d-m-Y')}}</td>
                                <td>{{Auth::user()->user_name}}</td>
                                <td>{{$designation->name ?? ''}}</td>
                                <td>{{$department->name ?? ''}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <form method="post" action="{{ route('payroll.verify', $payroll->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal decline -->
    <div class="modal modal-lg fade" id="Modaldecline" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Verification (Decline)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('payroll.decline',$payroll->id) }}">
                @csrf
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $department = Department::find(Auth::user()->department_id);
                                $designation = Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{date('d/m/y')}}</td>
                                <td>{{Auth::user()->user_name}}</td>
                                <td>{{$designation->name ?? ''}}</td>
                                <td>{{$department->name ?? ''}}</td>
                                <td><input type="text" name="reason" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>

                    <input type="text" name="status" value="Declined" hidden>
                    <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cancel -->
    <div class="modal modal-lg fade" id="Modalcancel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Verification (Cancel)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('payroll.cancel', $payroll->id) }}">
                    @csrf
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $department = Department::find(Auth::user()->department_id);
                                $designation = Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{date('d/m/y')}}</td>
                                <td>{{Auth::user()->user_name}}</td>
                                <td>{{$designation->name ?? ''}}</td>
                                <td>{{$department->name ?? ''}}</td>
                                <td><input type="text" name="reason" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>

                    <input type="text" name="status" value="Cancelled" hidden>
                    <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </div>
            </div>
        </div>
    </div>


    </div>
    <script>
        var data = "{{ route('payroll_detail.index',$payroll->id) }}";
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
                        // Include server-side pagination parameters
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
