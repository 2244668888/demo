@extends('layouts.app')
@section('title')
    Aging Report
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
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="filter-customer" class="form-control">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filter-supplier" class="form-control">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filter-payment-status" class="form-control">
                        <option value="">Select Payment Status</option>
                        <option value="due">Due</option>
                        <option value="partially_paid">Partially Paid</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button id="search-button" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable" id="Table">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Payment Status</th>
                            <th>Term</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Outstanding Balance</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let table = null;
    
            $('#search-button').on('click', function() {
                if (table) {
                    table.destroy(); 
                }
    
                table = $('#Table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('aging_report.data') }}",
                        type: 'GET',
                        data: function(d) {
                            d.customer_id = $('#filter-customer').val();
                            d.supplier_id = $('#filter-supplier').val();
                            d.payment_status = $('#filter-payment-status').val();
                        },
                         dataType: 'json'
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'invoice_no', name: 'invoice_no' },
                        { data: 'date', name: 'date' },
                        { data: 'payment_status', name: 'payment_status' },
                        { data: 'term', name: 'term' },
                        { data: 'total_amount', name: 'total_amount' },
                        { data: 'paying_amount', name: 'paying_amount' },
                        { data: 'outstanding_balance', name: 'outstanding_balance' }
                    ],
                    paging: true,
                    searching: false,
                    ordering: false,
                });
            });
        });
    </script>
    
    
@endsection
