@extends('layouts.app')
@section('title')
    PURCHASE ORDER LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('purchase_order.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .all_column{
        background: transparent;
    color: white;

    }
</style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable" >
                   <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>PO No.</th>
                            <th>Quotation Ref No.</th>
                            <th>Supplier</th>
                            <th>Net Total</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search REF No">
                            </th>

                            <th>
                                <input type="text" class="all_column " placeholder="search Quotation No">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Supplier">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Net Total">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Created Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Status">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Payment Status">
                            </th>



                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" method="POST" action="{{ route('payments.store') }}">
                        @csrf
                        <input type="hidden" name="purchase_order_id" id="purchase_order_id">
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
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="account_id">Account</label>
                                <select name="account_id" id="account_id" class="form-control">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                <span id="noAccountsMessage" style="color: red; display: none;">No accounts in bank</span>
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
        var data = "{{ route('purchase_order.data') }}";
        $(document).ready(function() {
            let bool = true;
            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        if (bool) {
                            d.order = [null, null];
                        } else {
                            d.order = d.order || [null,
                                null
                            ]; // Add sorting information with a default value
                        }
                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    }, {
                        data: 'quotation_ref_no',
                        name: 'quotation_ref_no',
                    },
                    {
                        data: 'supplier.name',
                        name: 'supplier.name',
                    },

                    {
                        data: 'net_total',
                        name: 'net_total',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;
        });

        function AjaxCall(columnsData) {

            $('.datatable').DataTable().destroy();

            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        d.order = d.order || [null, null]; // Add sorting information with a default value
                        d.columnsData = columnsData;

                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    }, {
                        data: 'quotation_ref_no',
                        name: 'quotation_ref_no',
                    },
                    {
                        data: 'supplier.name',
                        name: 'supplier.name',
                    },

                    {
                        data: 'net_total',
                        name: 'net_total',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });

        }

        var typingTimer;
        var doneTypingInterval = 1000; // Adjust the time interval as needed (in milliseconds)

        $('.datatable .all_column').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                var columnIndex = $(this).closest('th').index();

                // Collect all column indices and values in an array
                var columnsData = $('.datatable .all_column').map(function() {
                    var index = $(this).closest('th').index();
                    var value = $(this).val();
                    return {
                        index: index,
                        value: value
                    };
                }).get();
                AjaxCall(columnsData);

                // Focus on the input in the same column after making the Ajax call
                $(this).closest('tr').find('th').eq(columnIndex).find('input').focus();
            }, doneTypingInterval);
        });

        $('#addPaymentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var totalAmount = button.data('total-amount');
            var purchaseOrderId = button.data('id'); 
            var remainingBalance = button.data('remaining-balance');
            var modal = $(this);
            modal.find('#total_amount').val(totalAmount);
            modal.find('#purchase_order_id').val(purchaseOrderId); 
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
                    noAccountsMessage.show(); 
                }

            } else if (selectedMethod === 'credit') {
                accountSelect.append('<option value="">Select Account Payable</option>');
                @foreach($accounts as $account)
                    if ("{{ $account->type }}" === "liability") {
                        accountSelect.append('<option value="{{ $account->id }}">{{ $account->name }}</option>');
                    }
                @endforeach
            }
        });


        $(document).ready(function() {
            $('#viewPaymentsModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var purchaseOrderId = button.data('id');
                $.ajax({
                    url: '{{ route('payments.history', '') }}/' + purchaseOrderId,
                    method: 'GET',
                    success: function(response) {
                        let html = '<div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Ref No</th><th>Amount</th><th>Account</th><th>Payment Method</th><th>Remaining Balance</th><th>Payment Note</th> </tr></thead><tbody>';
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
                            html += '<td>' + (payment.purchase_order ? payment.purchase_order.ref_no : 'N/A') + '</td>'; 
                            html += '<td>' + payment.paying_amount + '</td>'; 
                            html += '<td>' + payment.account.name + '</td>'; 
                            html += '<td>' + payment.payment_method + '</td>';
                            html += '<td>' + payment.remaining_balance + '</td>'; 
                            html += '<td>' + payment.payment_note + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table></div>';
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
