@extends('layouts.app')

@section('content')
    @section('title')
        LEDGER STATEMENT
    @endsection
    @section('button')
        <a href="{{ route('ledger.export', ['format' => 'excel'] + request()->all()) }}" class="btn btn-warning">
            <i class="bi bi-download"></i> Export
        </a>
        <a href="{{ route('ledger.export', ['format' => 'pdf'] + request()->all()) }}" class="btn btn-danger">
            Export to PDF
        </a>
    @endsection

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Options</h5>
        </div>
        <div class="card-body">
            <form id="search-form" method="GET" action="{{ route('ledger.index') }}">
                <div class="row justify-content-center m-auto">
                    <div class="col-md-4">
                        <label for="date_range">Date Range:</label>
                        <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date Range" value="{{ request('date_range') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="accounts">Accounts</label>
                        <select name="accounts[]" id="account_names" class="form-control" multiple aria-label="Default select">
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ in_array($account->id, request('accounts', [])) ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Ledger</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Assets</strong></td>
                <td colspan="5"><strong>Group</strong></td>
            </tr>
            @php
                $assetsAccounts = $accounts->where('type', 'asset');
            @endphp
            @if ($assetsAccounts->count() > 0)
                @foreach ($assetsAccounts as $account)
                    @php
                        $runningBalance = $account->opening_balance;
                    @endphp
                    @foreach ($account->transactions as $transaction)
                        @if($account->category_id === null)
                            <tr>
                                <td class="indent-category">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance += $transaction->amount;
                                        } else {
                                            $runningBalance -= $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @else
                                @if ($loop->first || $previousCategoryId !== $account->category_id)
                                    <tr class="group-row-cat">
                                        <td class="indent-category">{{ $account->category->name }}</td>
                                        <td colspan="5">Group</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="indent-account">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $account->code }} {{ $account->name }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        @if ($transaction->type == 'debit')
                                            {{ number_format($transaction->amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->type == 'credit')
                                            {{ number_format($transaction->amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if ($transaction->type == 'debit') {
                                                $runningBalance += $transaction->amount;
                                            } else {
                                                $runningBalance -= $transaction->amount;
                                            }
                                        @endphp
                                        {{ number_format($runningBalance, 2) }}
                                    </td>
                                </tr>
                            @endif
                            @php $previousCategoryId = $account->category_id; @endphp
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="6">No transactions under Assets.</td>
                </tr>
            @endif

            <!-- Liabilities and Equity Section -->
            <tr>
                <td><strong>Liabilities and Equity</strong></td>
                <td colspan="5"><strong>Group</strong></td>
            </tr>
            @php
                $liabilityEquityAccounts = $accounts->whereIn('type', ['liability', 'equity']);
            @endphp
            @if ($liabilityEquityAccounts->count() > 0)
                @foreach ($liabilityEquityAccounts as $account)
                    @php
                        $runningBalance = $account->opening_balance;
                    @endphp
                    @foreach ($account->transactions as $transaction)
                        @if($account->category_id === null)
                            <tr>
                                <td class="indent-category">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance -= $transaction->amount;
                                        } else {
                                            $runningBalance += $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @else
                            @if ($loop->first || $previousCategoryId !== $account->category_id)
                                <tr class="group-row-cat">
                                    <td class="indent-category">{{ $account->category->name }}</td>
                                    <td colspan="5">Group</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="indent-account">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance -= $transaction->amount;
                                        } else {
                                            $runningBalance += $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @endif
                        @php $previousCategoryId = $account->category_id; @endphp
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="6">No transactions under Liabilities or Equity.</td>
                </tr>
            @endif

            <!-- Income Section -->
            <tr>
                <td><strong>Income</strong></td>
                <td colspan="5"><strong>Group</strong></td>
            </tr>
            @php
                $incomeAccounts = $accounts->where('type', 'income');
            @endphp
            @if ($incomeAccounts->count() > 0)
                @foreach ($incomeAccounts as $account)
                    @php
                        $runningBalance = $account->opening_balance;
                    @endphp
                    @foreach ($account->transactions as $transaction)
                        @if($account->category_id === null)
                            <tr>
                                <td class="indent-category">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance -= $transaction->amount;
                                        } else {
                                            $runningBalance += $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @else
                            @if ($loop->first || $previousCategoryId !== $account->category_id)
                                <tr class="group-row-cat">
                                    <td class="indent-category">{{ $account->category->name }}</td>
                                    <td colspan="5">Group</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="indent-account">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance -= $transaction->amount;
                                        } else {
                                            $runningBalance += $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @endif
                        @php $previousCategoryId = $account->category_id; @endphp
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="6">No transactions under Income.</td>
                </tr>
            @endif

            <!-- Expenses Section -->
            <tr>
                <td><strong>Expenses</strong></td>
                <td colspan="5"><strong>Group</strong></td>
            </tr>
            @php
                $expensesAccounts = $accounts->where('type', 'expense');
            @endphp
            @if ($expensesAccounts->count() > 0)
                @foreach ($expensesAccounts as $account)
                    @php
                        $runningBalance = $account->opening_balance;
                    @endphp
                    @foreach ($account->transactions as $transaction)
                        @if($account->category_id === null)
                            <tr>
                                <td class="indent-category">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance += $transaction->amount;
                                        } else {
                                            $runningBalance -= $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @else
                            @if ($loop->first || $previousCategoryId !== $account->category_id)
                                <tr class="group-row-cat">
                                    <td class="indent-category">{{ $account->category->name }}</td>
                                    <td colspan="5">Group</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="indent-account">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $account->code }} {{ $account->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if ($transaction->type == 'debit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 'credit')
                                        {{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if ($transaction->type == 'debit') {
                                            $runningBalance += $transaction->amount;
                                        } else {
                                            $runningBalance -= $transaction->amount;
                                        }
                                    @endphp
                                    {{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @endif
                        @php $previousCategoryId = $account->category_id; @endphp
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="6">No transactions under Expenses.</td>
                </tr>
            @endif
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
            
            document.getElementById('clear-filters').addEventListener('click', function() {
                document.getElementById('date_range').value = '';
                document.getElementById('account_names').value = '';
                document.getElementById('search-form').submit();
            });
        });
    </script>
@endsection
