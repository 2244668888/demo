@extends('layouts.app')

@section('content')
@section('title')
        ACCOUNT DETAILS
    @endsection
    @section('button')
        <a type="button" class="btn btn-info" href="{{ route('accounts.create') }}">
            <i class="bi bi-plus-square"></i> Add
        </a>
        <a href="{{ route('accounts.export-excel', request()->all()) }}" class="btn btn-warning">
            <i class="bi bi-download"></i> Export</a>
        <a href="{{ route('accounts.export-pdf', request()->all()) }}" class="btn btn-danger">Export to PDF</a>
    @endsection

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filter Accounts</h5>
    </div>
    <div class="card-body">
        <form id="filter-form" action="{{ route('accounts.index') }}" method="GET">
            <div class="row justify-content-center m-auto">
                <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="date_range">Date Range:</label>
                        <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date Range" value="{{ request('date_range') }}">
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="account_names">Account Name</label>
                        <select name="account_names[]" id="account_names" class="form-select" multiple aria-label="Default select" data-live-search="true">
                            <option value="">Select Account Name</option>
                            @foreach($accountNames as $name)
                                <option value="{{ $name }}" {{ in_array($name, request()->input('account_names', [])) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="type">Account Type</label>
                        <select name="type" id="type" class="form-select" aria-label="Default select">
                            <option value="">Select Account Type</option>
                            <option value="asset" {{ request()->input('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                            <option value="liability" {{ request()->input('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                            <option value="equity" {{ request()->input('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                            <option value="income" {{ request()->input('type') == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ request()->input('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-4 ">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="my-4">
    <div class="d-flex justify-content-between align-items-center">
        <p class="mt-1 me-2">Please create basic accounts first if you did not create them yet, e.g. <span class="fw-bold">(Cash, Inventory, Revenue, Salaries, Account Payable, Account Receivable, Owners Equity)</span></p>
        <div class="d-flex align-items-center">
            <h5 class="mt-1 me-2">LEDGER</h5>
            <a class="btn btn-sm circle green" href="#" title="Type"><i class="bi bi-circle-fill"></i></a>
            <a class="btn btn-sm circle red" href="#" title="Current & Non Current"><i class="bi bi-circle-fill"></i></a>
            <a class="btn btn-sm circle blue" href="#" title="Categories"><i class="bi bi-circle-fill"></i></a>
        </div>
    </div>
</div>

<div class="table-responsive my-4">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Type</th>
                <th>Opening Balance </th>
                <th>Current Balance </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>


            <tr>
                <td colspan="5" class="group-row group-asset">Assets</td>
            </tr>
            <tr>
                <td class="group-row-cat group-noncurrent">Non-current Assets</td>
                <td colspan="4" class="group-row-cat">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'asset')->where('categoryType', 'non-current') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat group-category">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            <tr>
                <td class="group-row-cat group-current">Current Assets</td>
                <td colspan="4" class="group-row-cat">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'asset')->where('categoryType', 'current') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat group-category">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            <tr >
                <td colspan="5" class="group-row group-asset">Liabilities</td>
            </tr>
            <tr>
                <td class="group-row-cat group-noncurrent">Non-current Liabilities</td>
                <td colspan="4">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'liability')->where('categoryType', 'non-current') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            <tr>
                <td class="group-row group-current">Current Liabilities</td>
                <td colspan="4">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'liability')->where('categoryType', 'current') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            <tr>
                <td class="group-row">Equity</td>
                <td colspan="4">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'equity') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            {{-- INCOME SECTION --}}
            <tr>
                <td class="group-row">Income</td>
                <td colspan="4">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'income') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

            {{-- EXPENSE SECTION --}}
            <tr>
                <td class="group-row">Expenses</td>
                <td colspan="4">Group</td>
            </tr>
            @foreach ($accounts->where('type', 'expense') as $account)
                @if ($account->category_id === null)
                    <tr>
                        <td class="indent-category">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @else
                    @if ($loop->first || $previousCategoryId !== $account->category_id)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                            <td colspan="4">Group</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="indent-account">[{{$account->code ?? ''}}] {{ ucfirst($account->name) }}</td>
                        <td>Ledger</td>
                        <td>{{ number_format($account->opening_balance, 2) }}</td>
                        <td>{{ number_format($account->calculateBalance(), 2) }}</td>
                        <td>
                            <a href="{{ route('ledger.ledgerAccount', $account->id) }}" class="btn btn-sm btn-primary" title="View Ledger"><i class="bi bi-view-stacked"></i></a>
                            <a href="{{ route('transactions.create', $account->id) }}" class="btn btn-sm btn-success" title="Add Transaction"><i class="bi bi-database-add"></i></a>
                            <a href="{{ route('reconciliation.index', $account->id) }}" class="btn btn-sm btn-warning" title="Reconcile Account"><i class="bi bi-journal-check"></i></a>
                        </td>
                    </tr>
                @endif
                @php $previousCategoryId = $account->category_id; @endphp
            @endforeach

        </tbody>
    </table>
</div>
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
                'Last 3 Years': [moment().subtract(3, 'years').startOf('year'), moment().endOf('year')],
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
    });

</script>
@endsection