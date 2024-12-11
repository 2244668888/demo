@extends('layouts.app')

@section('content')

@php
    $startDate = null;
    $endDate = null;
    if (request('date_range')) {
        [$startDate, $endDate] = explode(' - ', request('date_range'));
    }
    $currentYear = date('Y');
    $profit = $totalIncome - $cogs;
    $netIncome = $profit - $totalExpenses;
@endphp

@section('title')
    PROFIT AND LOSS
@endsection

@section('button')
    <a href="{{ route('reports.profit_loss.export', ['format' => 'excel'] + request()->all()) }}" class="btn btn-warning">
        <i class="bi bi-download"></i> Export</a>
    <a href="{{ route('reports.profit_loss.export', ['format' => 'pdf'] + request()->all()) }}" class="btn btn-danger">Export to PDF</a>
@endsection

<div class="card my-4">
    <div class="card-header">
        <h5 class="mb-0">Filter with Date</h5>
    </div>
    <div class="card-body">
        <form id="search-form" method="GET" action="{{ route('reports.profit_loss') }}">
            <div class="row justify-content-center m-auto">
                <div class="col-md-4">
                    <label for="date_range">Date Range:</label>
                    <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date Range" value="{{ request('date_range') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-end">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="my-4">
    <div class="d-flex justify-content-end align-items-center">
        <h5 class="mt-1 me-2">LEDGER</h5>
        <a class="btn btn-sm circle green" href="#" title="Type"><i class="bi bi-circle-fill"></i></a>
        <a class="btn btn-sm circle blue" href="#" title="Categories"><i class="bi bi-circle-fill"></i></a>
    </div>
</div>

<!-- Income Section -->
<table class="table">
    <thead>
        <tr>
            <th class="group-row group-asset">Income</th>
            <th class="text-end">Amount {{$currentYear}} MYR</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($income as $account)
            @if($account->category_id === null)
                <tr>
                    <td class="indent-category">{{ $account->code ?? '' }} {{ $account->name }}</td>
                    <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                </tr>
            @else
                @if ($loop->first || $previousCategoryId !== $account->category_id)
                    <tr class="group-row-cat group-category">
                        <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="indent-account">{{ $account->code ?? '' }} {{ $account->name }}</td>
                    <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                </tr>
            @endif
            @php $previousCategoryId = $account->category_id; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><strong>Total Income</strong></td>
            <td class="text-end"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- CoGS Section -->
<table class="table">
    <thead>
        <tr>
            <th class="group-row group-asset">Cost of Goods Sold (CoGS)</th>
            <th class="text-end">Amount {{$currentYear}} MYR</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Cogs</td>
            <td class="text-end">{{ number_format($cogs, 2) }} MYR</td>
        </tr>
    </tbody>
</table>

<table class="table">
    <tfoot>
        <tr>
            <td><strong>Gross Profit</strong></td>
            <td class="text-end"><strong>{{ number_format($profit, 2) }} MYR</strong></td>
        </tr>
    </tfoot>
</table>


<table class="table">
    <thead>
        <tr>
            <th class="group-row group-asset">Expenses</th>
            <th class="text-end">Amount {{$currentYear}} MYR</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($expenses as $account)
            @if($account->category_id === null)
                <tr>
                    <td class="indent-category">{{ $account->code ?? '' }} {{ $account->name }}</td>
                    <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                </tr>
            @else
                @if ($loop->first || $previousCategoryId !== $account->category_id)
                    <tr class="group-row-cat group-category">
                        <td class="indent-category indent-category-color">{{ $account->category->name }}</td>
                        <td colspan="1"></td>
                    </tr>
                @endif
                <tr>
                    <td class="indent-account">{{ $account->code ?? '' }} {{ $account->name }}</td>
                    <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                </tr>
            @endif
            @php $previousCategoryId = $account->category_id; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><strong>Total Expenses</strong></td>
            <td class="text-end"><strong>{{ number_format($totalExpenses, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>


<h2>Summary</h2>
<table class="table">
    <tbody>
        <tr>
            <td><strong>Net Profit (or Loss)</strong></td>
            <td class="text-end">
                <strong>
                    {{ $netIncome < 0 ? '(' . number_format(abs($netIncome), 2) . ')' : number_format($netIncome, 2) }} MYR
                </strong>
            </td>
        </tr>
        <tr>
            <td><strong>Retained Earnings B/F</strong></td>
            <td class="text-end">
                <strong>
                    {{ $carryforwardBalance < 0 ? '(' . number_format(abs($carryforwardBalance), 2) . ')' : number_format($carryforwardBalance, 2) }} MYR
                </strong>
            </td>
        </tr>
        <tr>
            <td><strong>Retained Earnings C/F</strong></td>
            <td class="text-end">
                <strong>
                    @php
                       $retainedEarningsCF = $netIncome + $carryforwardBalance;
                    @endphp
                    {{ $retainedEarningsCF < 0 ? '(' . number_format(abs($retainedEarningsCF), 2) . ')' : number_format($retainedEarningsCF, 2) }} MYR
                </strong>
            </td>
        </tr>
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

        $('#clear-filters').click(function() {
            $('#date_range').val('');
            $('#search-form').submit();
        });
    });
</script>

@endsection
