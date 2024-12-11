@extends('layouts.app')
@section('title')
PAYROLL LIST
@endsection
@section('button')
<button type="button" class="btn btn-warning" id="export-btn">
    <i class="bi bi-download"></i> Export
</button>
    <a type="submit" class="btn btn-info" href="{{ route('payroll.generateStore') }}">
        <i class="bi bi-file-earmark-arrow-down-fill"></i> Generate
    </a>
@endsection
@section('content')
    <style>
       .table thead tr input {
            background: transparent;
            color: white;

        }
        .border-blue {
            border-block-color: darkblue;
        }

        .text-blue{
            color: darkblue;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable" id="Table">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Payroll Date</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-year" placeholder="Search Year"></th>
                            <th><input type="text" id="search-month" placeholder="Search Month"></th>
                            <th><input type="text" id="search-payroll_date" placeholder="Search Payroll Date Name"></th>
                            <th><input type="text" id="search-status" placeholder="Search Status"></th>
                            <th><input type="text" id="search-payment-status" placeholder="Search Payment Status"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->order_no }}</td>
                                <td>{{ $order->po_no }}</td>
                                <td>{{ $order->customers->name }}</td>
                                <td>{{ $order->date }}</td>
                                <td>
                                    @if ($order->status == 'in-progress')
                                        <span class="badge border border-warning text-warning">In Progress</span>
                                    @elseif ($order->status == 'complete')
                                        <span class="badge border border-success text-success">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('order.view', $order->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    @if ($order->status == 'in-progress')
                                        <a class="btn btn-info btn-sm" href="{{ route('order.edit', $order->id) }}"><i
                                                class="bi bi-pencil"></i></a>
                                    @endif
                                    <a class="btn btn-danger btn-sm" href="{{ route('order.destroy', $order->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" method="POST" action="{{ route('payments.storePayroll') }}">
                        @csrf
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_amount">Total Amount</label>
                                <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="paying_amount">Remaining Balance</label>
                                <input type="text" name="remaining_amount" id="remaining_amount" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="paying_amount">Paying Amount</label>
                                <input type="number" name="paying_amount" id="paying_amount" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="balance">Balance</label>
                                <input type="text" name="balance" id="balance" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="">Select Payment Type</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="account_id">Account</label>
                                <select name="account_id" id="account_id" class="form-control">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_note">Payment Note</label>
                                <textarea name="payment_note" id="payment_note" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Add Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewPaymentsModal" tabindex="-1" aria-labelledby="viewPaymentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPaymentsModalLabel">Payment History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   
                </div>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('payroll.index') }}";
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
                        d.year = $('#search-year').val();
                        d.month = $('#search-month').val();
                        d.date = $('#search-date').val();
                        d.status = $('#search-status').val();
                        d.payment_status = $('#search-payment-status').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'year',
                        name: 'year',
                    }, {
                        data: 'month',
                        name: 'month',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data === 'Preparing') {
                                return '<span class="badge border border-light text-light">Preparing</span>';
                            } else if (data === 'Pending') {
                                return '<span class="badge border border-warning text-warning">Pending</span>';
                            }
                             else if (data === 'Pending') {
                                return '<span class="badge border border-warning text-warning">Pending</span>';
                            }
                             else if (data === 'Approved') {
                                return '<span class="badge border border-blue text-blue">Approved</span>';
                            }
                             else if (data === 'Pending') {
                                return '<span class="badge border border-warning text-warning">Pending</span>';
                            }
                            else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        render: function (data, type, row) {
                            if (row.status === 'Approved' || row.status === 'Completed') {
                                if (data === 'due') {
                                    return '<span class="badge border border-danger text-danger">Due</span>';
                                } else if (data === 'partially_paid') {
                                    return '<span class="badge border border-warning text-warning">Partially Paid</span>';
                                } else if (data === 'paid') {
                                    return '<span class="badge border border-success text-success">Paid</span>';
                                }
                            }
                            return '<span class="badge border border-secondary">N/A</span>';
                        }
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

            table.on('xhr.dt', function (e, settings, json, xhr) {
                let showPaymentStatusColumn = json.data.some(row => row.status === 'Approved' || row.status === 'Completed');
                table.column(5).visible(showPaymentStatusColumn); 
                if (showPaymentStatusColumn) {
                    $('#search-payment-status').closest('th').show();
                } else {
                    $('#search-payment-status').closest('th').hide();
                }
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
            const table = document.getElementById("Table");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"PAYROLL LIST"');
            csv.push(""); 
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
            link.setAttribute("download", "payroll.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);

        $('#addPaymentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var totalAmount = button.data('total-amount');
            var payrollId = button.data('id'); 
            var remainingBalance = button.data('remaining-balance');
            var modal = $(this);
            modal.find('#total_amount').val(totalAmount);
            modal.find('#payroll_id').val(payrollId); 
            modal.find('#remaining_amount').val(remainingBalance);
            modal.find('#balance').val(remainingBalance);
            $('#paying_amount').on('input', function () {
                var payingAmount = parseFloat($(this).val()) || 0;
                var remainingBalance = parseFloat($('#remaining_amount').val()) || 0;
                var balance = remainingBalance - payingAmount;
                $('#balance').val(balance < 0 ? 0 : balance);
            });
        });

        $('#payment_method').change(function() {
            var selectedMethod = $(this).val();
            var accountSelect = $('#account_id');
            accountSelect.empty(); 

            if (selectedMethod === 'cash') {
                accountSelect.append('<option value="">Select Cash Account</option>');
                @foreach($accounts as $account)
                    if ("{{ $account->name }}" === "Cash") {
                        accountSelect.append('<option value="{{ $account->id }}">{{ $account->name }}</option>');
                    }
                @endforeach
            } else if (selectedMethod === 'bank') {
                accountSelect.append('<option value="">Select Bank Account</option>');
                let bankAccountsFound = false;
                let bankCategoryExists = "{{ $bankCategoryId }}" !== "null";

                if (bankCategoryExists) {
                    @foreach($accounts as $account)
                        if ("{{ $account->category_id }}" === "{{ $bankCategoryId }}") {
                            accountSelect.append('<option value="{{ $account->id }}">{{ $account->name }}</option>');
                            bankAccountsFound = true; 
                        }
                    @endforeach
                }

                if (!bankAccountsFound) {
                    accountSelect.append('<option value="">You don\'t have bank accounts available</option>');
                }
            }
        });

        $(document).ready(function() {
            $('#viewPaymentsModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var payrollId = button.data('id');
                $.ajax({
                    url: '{{ route('payments.payrollhistory', '') }}/' + payrollId,
                    method: 'GET',
                    success: function(response) {
                        let html = '<table class="table"><thead><tr><th>Date</th><th>Payment No</th><th>Amount</th><th>Account</th><th>Payment Method</th><th>Remaining Balance</th></tr></thead><tbody>';
                        response.forEach(payment => {
                            html += '<tr>';
                            
                            // Format the date
                            const date = new Date(payment.created_at);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: false
                            });

                            html += '<td>' + formattedDate + '</td>';
                            html += '<td>' + (payment.invoice ? payment.payroll.id : 'N/A') + '</td>'; 
                            html += '<td>' + payment.paying_amount + '</td>'; 
                            html += '<td>' + payment.account.name + '</td>'; 
                            html += '<td>' + payment.payment_method + '</td>'; 
                            html += '<td>' + payment.remaining_balance + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        $('#viewPaymentsModal .modal-body').html(html);
                    },
                    error: function() {
                        $('#viewPaymentsModal .modal-body').html('<p>Unable to fetch payment records. Please try again.</p>');
                    }
                });
            });
        });

    </script>


@endsection