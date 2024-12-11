@extends('layouts.app')
@section('title')
    PURCHASE PLANNING LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('purchase_planning.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .table thead tr input {
         background: transparent;
         color: white;

     }
 </style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>PP No</th>
                            <th>Created Date</th>
                            <th>Current Status</th>
                            <th>By</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>

                        <tr>
                            <th></th>
                            <th><input type="text" id="search-ref_no" placeholder="Search PP No"></th>
                            <th><input type="text" id="search-date" placeholder="Search Created Date"></th>
                            <th><input type="text" id="search-status" placeholder="Search Current Status"></th>
                            <th><input type="text" id="search-user" placeholder="Search By"></th>
                            <th><input type="text" id="search-department" placeholder="Search Department"></th>

                            <th></th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                        {{-- @foreach ($purchase_plannings as $purchase_planning)
                            <tr>
                                @php
                                    $status = App\Models\PurchasePlanningVerification::where(
                                        'purchase_planning_id',
                                        $purchase_planning->id,
                                    )
                                        ->orderBy('id', 'DESC')
                                        ->first();
                                @endphp
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase_planning->ref_no }}</td>
                                <td>{{ $purchase_planning->date }}</td>
                                <td>
                                    @if ($status)
                                        {{ $status->user->user_name ?? '' }}
                                    @endif
                                </td>
                                <td>
                                    @if ($status)
                                        {{ $status->designation->name ?? '' }}
                                    @endif
                                </td>
                                <td>
                                    @if ($status)
                                        <span
                                            class="badge border
                                            @if (strtolower($status->status) == 'prepared') border-light text-light
                                            @elseif(strtolower($status->status) == 'checked') border-warning text-warning
                                            @elseif(strtolower($status->status) == 'verified(hod)' || strtolower($status->status) == 'verified(acc)') border-info text-info
                                            @elseif(strtolower($status->status) == 'declined') border-warning text-warning
                                            @elseif(strtolower($status->status) == 'cancelled') border-danger text-danger
                                            @elseif(strtolower($status->status) == 'approved') border-primary text-primary @endif">
                                            {{ $status->status }}
                                        </span>
                                    @else
                                        <span class="badge border border-light text-light">Prepared</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($status)
                                        @if (strtolower($status->status) == 'prepared' || strtolower($status->status) == 'declined')
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('purchase_planning.edit', $purchase_planning->id) }}"
                                                title="Edit"><i class="bi bi-pencil"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ route('purchase_planning.destroy', $purchase_planning->id) }}"
                                                title="Delete"><i class="bi bi-trash"></i></a>
                                        @endif
                                        @if (strtolower($status->status) == 'prepared')
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('purchase_planning.verification', ['id' => $purchase_planning->id, 'action' => 'check']) }}"
                                                title="Check"><i class="bi bi-check"></i></a>
                                        @endif
                                        @if (strtolower($status->status) == 'checked')
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('purchase_planning.verification', ['id' => $purchase_planning->id, 'action' => 'verify_hod']) }}"
                                                title="Verify HOD"><i class="bi bi-check-circle"></i></a>
                                        @endif
                                        @if (strtolower($status->status) == 'verified(hod)')
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('purchase_planning.verification', ['id' => $purchase_planning->id, 'action' => 'verify_acc']) }}"
                                                title="Verify ACC"><i class="bi bi-check-circle"></i></a>
                                        @endif
                                        @if (strtolower($status->status) == 'verified(acc)')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('purchase_planning.verification', ['id' => $purchase_planning->id, 'action' => 'approve']) }}"
                                                title="Approve"><i class="bi bi-check-circle-fill"></i></a>
                                        @endif
                                        @if (strtolower($status->status) == 'cancelled')
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ route('purchase_planning.destroy', $purchase_planning->id) }}"
                                                title="Delete"><i class="bi bi-trash"></i></a>
                                        @endif
                                    @else
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('purchase_planning.edit', $purchase_planning->id) }}"
                                            title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a class="btn btn-danger btn-sm"
                                            href="{{ route('purchase_planning.destroy', $purchase_planning->id) }}"
                                            title="Delete"><i class="bi bi-trash"></i></a>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('purchase_planning.verification', ['id' => $purchase_planning->id, 'action' => 'check']) }}"
                                            title="Check"><i class="bi bi-check"></i></a>
                                    @endif
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('purchase_planning.view', $purchase_planning->id) }}"
                                        title="View"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('purchase_planning.data') }}";
        $(document).ready(function() {
            let bool = true;
            var table = $('.datatable').DataTable({
                perPageSelect: [5, 10, 15, ["All", -1]],
                processing: true,
                serverSide: true,
                language: {
                    processing: 'Processing' // Custom processing text
                },
                ajax: {
                    url: data, // URL for your server-side data endpoint
                    type: 'GET',
                    data: function (d) {
                        d.ref_no = $('#search-ref_no').val();
                        d.date = $('#search-date').val();
                        d.status = $('#search-status').val();
                        d.user = $('#search-user').val();
                        d.department = $('#search-department').val();

                    }
                }, // URL to fetch data
                columns: [{
                        data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'verification_status',
                        name: 'verification_status',
                        defaultContent: '',
                        render: function(data, type, row) {
                            console.log(row);
                            if (row.latest_verification) {
                if (!row.latest_verification.status) {
                    return '<span class="badge border border-secondary text-secondary">Unknown</span>';
                }
                let status = row.latest_verification.status.toLowerCase(); // Ensure the status is in lowercase for comparison
                let badgeClass = '';

                if (status === 'prepared') {
                    badgeClass = 'badge border border-light text-light';
                } else if (status === 'checked') {
                    badgeClass = 'badge border border-warning text-warning';
                } else if (status === 'verified(hod)' || status === 'verified(acc)') {
                    badgeClass = 'badge border border-info text-info';
                } else if (status === 'declined' || status === 'cancelled') {
                    badgeClass = 'badge border border-danger text-danger';
                } else if (status === 'approved') {
                    badgeClass = 'badge border border-primary text-primary';
                } else {
                    badgeClass = 'badge border border-secondary text-secondary';
                }
                return '<span class="' + badgeClass + '">' + row.latest_verification.status.charAt(0).toUpperCase() + row.latest_verification.status.slice(1) + '</span>';
                    }
                }
                },
                    {
                        data: 'user.user_name',
                        name: 'user.user_name',
                        defaultContent: ''
                    },
                    {
                        data: 'department_name', 
                        name: 'department_name',
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
            const table = document.getElementById("myTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td, th");
                let row = [];
                for (let j = 0; j < cells.length; j++) {
                    row.push('"' + cells[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }

            const csvContent = "\uFEFF" + csv.join("\n");
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });

            // if we're using IE/Edge, then use different download call
            if (typeof navigator.msSaveOrOpenBlob === 'function') {
                navigator.msSaveOrOpenBlob(blob, "leave.csv");
            } else {
                // the next code will generate a temp <a /> tag that you can trigger a hidden click for it to start downloading
                const link = document.createElement('a');
                const csvUrl = URL.createObjectURL(blob);

                link.href = csvUrl;
                link.setAttribute('download', "leave.csv");

                // set the visibility hidden so that there is no effect on your web-layout
                link.style.visibility = 'hidden';

                // finally we will append the anchor tag and remove it after clicking it
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }



            // var tableClone = $("#myTable").clone();

            // var wb = XLSX.utils.table_to_book(tableClone[0], { sheet: "Sheet1" });

            // XLSX.writeFile(wb, 'leave.xlsx');
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
