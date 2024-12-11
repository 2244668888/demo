@extends('layouts.app')

@section('content')
    @section('title')
        TRIAL BALANCE
    @endsection
    @section('button')
        <a href="{{ route('trial_balance.export', request()->all()) }}" class="btn btn-warning">
            <i class="bi bi-download"></i> Export
        </a>
        <a href="{{ route('trial_balance.pdf', request()->all()) }}" class="btn btn-danger">
            Export to PDF
        </a>
    @endsection

    <style>
        .opening-balance-column {
            display: none;
        }
    </style>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter Accounts</h5>
    </div>
    <div class="card-body">
        <form id="filter-form" action="{{ route('reports.trial_balance') }}" method="GET">
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-12">
                    <label for="date_range">Date Range:</label>
                    <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date Range" value="{{ request('date_range') }}">
                </div>
                <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="account_names">Account Name</label>
                        <select name="account_names[]" id="account_names" class="form-select selectpicker" multiple data-live-search="true">
                            @foreach($accountNames as $account) 
                                <option value="{{ $account->id }}" {{ in_array($account->id, request()->input('account_names', [])) ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">Account Type</label>
                        <select name="account_type" id="account_type" class="form-control selectpicker">
                            <option value="">All</option>
                            @foreach ($accountTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('reports.trial_balance') }}" class="btn btn-secondary" id="clear-filters">Clear Filters</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="my-4">
    <div class="form-group">
        <label for="toggle-opening-balance">
            <input type="checkbox" id="toggle-opening-balance">
            Show With Opening Balance
        </label>
    </div>            
</div>
<table class="table">
    <thead>
        <tr>
            <th>Account Name</th>
            <th>Type</th>
            <th class="opening-balance-column">Opening Balance</th>
            <th>Debit Total</th>
            <th>Credit Total</th>
            <th>C/L Balance</th>
        </tr>
    </thead>
    <tbody>
        @php $previousCategory = null; @endphp
        @foreach ($groupedAccounts as $type => $accounts)
            <tr>
                <td><strong>[{{ $loop->iteration }}] {{ ucfirst($type) }}</strong></td>
                <td colspan="4"><strong>Group</strong></td>
            </tr>
            @foreach ($accounts as $account)
                @if ($account['category'] !== $previousCategory && $account['category'] !== null)
                    <tr class="group-row-cat">
                        <td>{{ $account['category'] }}</td>
                        <td colspan="4">Group</td>
                    </tr>
                @endif
                <tr>
                    <td class="{{ $account['category'] ? 'indent' : '' }}">{{ $account['account']->code ?? '' }} {{ $account['account']->name }}</td>
                    <td>Ledger</td>
                    <td class="opening-balance-column">{{ number_format($account['opening_balance'], 2) }}</td>
                    <td>{{ $account['debit_total'] > 0 ? 'Dr ' . number_format($account['debit_total'], 2) : '-' }}</td>
                    <td>{{ $account['credit_total'] > 0 ? 'Cr ' . number_format($account['credit_total'], 2) : '-' }}</td>
                    <td>{{ $account['closing_balance'] > 0 ? 'Dr ' . number_format($account['closing_balance'], 2) : 'Cr ' . number_format(abs($account['closing_balance']), 2) }}</td>
                </tr>
                @php $previousCategory = $account['category']; @endphp
            @endforeach
        @endforeach
    </tbody>
</table>

<script>   
    $(document).ready(function() {
        $('#date_range').daterangepicker({
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            locale: {
                format: 'DD-MM-YYYY'
            },
            ranges: {
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
                'Last Quarter': [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')],
                'Last 4 Quarters': [moment().subtract(1, 'year').startOf('quarter'), moment().endOf('quarter')],
                'Current Quarter': [moment().startOf('quarter'), moment().endOf('quarter')]
            }
        });
        $('#account_names').select2({
            placeholder: 'Select Account Names',
            allowClear: true
        });
        
        $('#clear-filters').on('click', function(e) {
            e.preventDefault();
            $('#account_names').val(null).trigger('change');
            $('#account_type').val('');
            $('#date_range').val('');
            $('#filter-form').submit(); 
        });
    });
    $('#toggle-opening-balance').on('change', function() {
        if ($(this).is(':checked')) {
            $('.opening-balance-column').show();
        } else {
            $('.opening-balance-column').hide();
        }
    });

    $('#toggle-opening-balance').trigger('change');
</script>
@endsection
