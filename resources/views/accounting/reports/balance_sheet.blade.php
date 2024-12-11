@extends('layouts.app')

@section('content')
@php
    $startDate = null;
    $endDate = null;
    if (request('date_range')) {
        [$startDate, $endDate] = explode(' - ', request('date_range'));
    }
    @endphp

    @section('title')
        BALANCE SHEET
    @endsection
    @section('button')
        <a href="{{ route('reports.balance_sheet.export', ['format' => 'excel'] + request()->all()) }}" class="btn btn-warning">
            <i class="bi bi-download"></i> Export</a>
        <a href="{{ route('reports.balance_sheet.export', ['format' => 'pdf'] + request()->all()) }}" class="btn btn-danger">Export to PDF</a>
    @endsection
    <div class="card my-4">
        <div class="card-header">
            <h5 class="mb-0">Filter with Date</h5>
        </div>
        <div class="card-body">
            <form id="search-form" method="GET" action="{{ route('reports.balance_sheet') }}">
                <div class="row justify-content-center m-auto">
                    <div class="col-md-4">
                        <label for="date_range">Date Range:</label>
                        <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date Range" value="{{ request('date_range') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
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
            <a class="btn btn-sm circle red" href="#" title="Current & Non Current"><i class="bi bi-circle-fill"></i></a>
            <a class="btn btn-sm circle blue" href="#" title="Categories"><i class="bi bi-circle-fill"></i></a>
        </div>
    </div>

    <div class="row">
        <!-- ASSETS -->
        <div class="col-md-6">
            <h2 class="group-row group-asset">Assets</h2>
            <h4 class="group-row-cat group-noncurrent">Non-Current Assets</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalNonCurrentAssetsByCategory = [];
                    @endphp

                    @foreach ($nonCurrentAssets as $asset)
                        @if ($asset->category)
                            @if (!isset($totalNonCurrentAssetsByCategory[$asset->category->id]))
                                @php
                                    $totalNonCurrentAssetsByCategory[$asset->category->id] = [
                                        'name' => $asset->category->name,
                                        'total' => 0,
                                        'accounts' => []
                                    ];
                                @endphp
                            @endif
                            @php
                                $assetBalance = $asset->calculateBalanceDate($startDate, $endDate);
                                $totalNonCurrentAssetsByCategory[$asset->category->id]['total'] += $assetBalance;
                                $totalNonCurrentAssetsByCategory[$asset->category->id]['accounts'][] = $asset;
                            @endphp
                        @else
                            <tr>
                                <td class="indent-category">{{ $asset->code ?? '' }}  {{ $asset->name }}</td>
                                <td class="text-end">{{ number_format($asset->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($totalNonCurrentAssetsByCategory as $category)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['total'], 2) }}</td>
                        </tr>
                        @foreach ($category['accounts'] as $account)
                            <tr>
                                <td class="indent-account">{{ $asset->code ?? '' }}  {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <h4 class="group-row-cat group-noncurrent">Current Assets</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalCurrentAssetsByCategory = [];
                    @endphp

                    @foreach ($currentAssets as $asset)
                        @if ($asset->category)
                            @if (!isset($totalCurrentAssetsByCategory[$asset->category->id]))
                                @php
                                    $totalCurrentAssetsByCategory[$asset->category->id] = [
                                        'name' => $asset->category->name,
                                        'total' => 0,
                                        'accounts' => []
                                    ];
                                @endphp
                            @endif
                            @php
                                $assetBalance = $asset->calculateBalanceDate($startDate, $endDate);
                                $totalCurrentAssetsByCategory[$asset->category->id]['total'] += $assetBalance;
                                $totalCurrentAssetsByCategory[$asset->category->id]['accounts'][] = $asset;
                            @endphp
                        @else
                            <tr>
                                <td class="indent-category">{{ $asset->code ?? '' }}  {{ $asset->name }}</td>
                                <td class="text-end">{{ number_format($asset->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($totalCurrentAssetsByCategory as $category)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['total'], 2) }}</td>
                        </tr>
                        @foreach ($category['accounts'] as $account)
                            <tr>
                                <td class="indent-account">{{ $account->code ?? '' }}  {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <tfoot>
                    <tr>
                        <th>Total Assets</th>
                        <th class="text-end">{{ number_format($totalAssets, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- EQUITY AND LIABILITIES -->
        <div class="col-md-6">
            <h2 class="group-row group-asset">Equity</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalEquityByCategory = [];
                    @endphp

                    @foreach ($equity as $equityAccount)
                        @if ($equityAccount->category)
                            @if (!isset($totalEquityByCategory[$equityAccount->category->id]))
                                @php
                                    $totalEquityByCategory[$equityAccount->category->id] = [
                                        'name' => $equityAccount->category->name,
                                        'total' => 0,
                                        'accounts' => []
                                    ];
                                @endphp
                            @endif
                            @php
                                $equityBalance = $equityAccount->calculateBalanceDate($startDate, $endDate);
                                $totalEquityByCategory[$equityAccount->category->id]['total'] += $equityBalance;
                                $totalEquityByCategory[$equityAccount->category->id]['accounts'][] = $equityAccount;
                            @endphp
                        @else
                            <tr>
                                <td class="indent-category">{{ $equityAccount->code ?? '' }} {{ $equityAccount->name }}</td>
                                <td class="text-end">{{ number_format($equityAccount->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($totalEquityByCategory as $category)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['total'], 2) }}</td>
                        </tr>
                        @foreach ($category['accounts'] as $account)
                            <tr>
                                <td class="indent-account">{{ $account->code ?? '' }} {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td>Retained Earnings</td>
                        <td class="text-end">
                            {{ $netIncome < 0 ? '(' . number_format(abs($netIncome), 2) . ')' : number_format($netIncome, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <h2 class="group-row group-asset">Liabilities</h2>
            <h4 class="group-row-cat group-noncurrent">Non-Current Liabilities</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalNonCurrentLiabilitiesByCategory = [];
                    @endphp

                    @foreach ($nonCurrentLiabilities as $liability)
                        @if ($liability->category)
                            @if (!isset($totalNonCurrentLiabilitiesByCategory[$liability->category->id]))
                                @php
                                    $totalNonCurrentLiabilitiesByCategory[$liability->category->id] = [
                                        'name' => $liability->category->name,
                                        'total' => 0,
                                        'accounts' => []
                                    ];
                                @endphp
                            @endif
                            @php
                                $liabilityBalance = $liability->calculateBalanceDate($startDate, $endDate);
                                $totalNonCurrentLiabilitiesByCategory[$liability->category->id]['total'] += $liabilityBalance;
                                $totalNonCurrentLiabilitiesByCategory[$liability->category->id]['accounts'][] = $liability;
                            @endphp
                        @else
                            <tr>
                                <td class="indent-category">{{ $liability->name }}</td>
                                <td class="text-end">{{ number_format($liability->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($totalNonCurrentLiabilitiesByCategory as $category)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['total'], 2) }}</td>
                        </tr>
                        @foreach ($category['accounts'] as $account)
                            <tr>
                                <td class="indent-account">{{ $account->code ?? '' }} {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <h4 class="group-row-cat group-noncurrent">Current Liabilities</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalCurrentLiabilitiesByCategory = [];
                    @endphp

                    @foreach ($currentLiabilities as $liability)
                        @if ($liability->category)
                            @if (!isset($totalCurrentLiabilitiesByCategory[$liability->category->id]))
                                @php
                                    $totalCurrentLiabilitiesByCategory[$liability->category->id] = [
                                        'name' => $liability->category->name,
                                        'total' => 0,
                                        'accounts' => []
                                    ];
                                @endphp
                            @endif
                            @php
                                $liabilityBalance = $liability->calculateBalanceDate($startDate, $endDate);
                                $totalCurrentLiabilitiesByCategory[$liability->category->id]['total'] += $liabilityBalance;
                                $totalCurrentLiabilitiesByCategory[$liability->category->id]['accounts'][] = $liability;
                            @endphp
                        @else
                            <tr>
                                <td class="indent-category">{{ $liability->code }} {{ $liability->name }}</td>
                                <td class="text-end">{{ number_format($liability->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach ($totalCurrentLiabilitiesByCategory as $category)
                        <tr class="group-row-cat">
                            <td class="indent-category indent-category-color">{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['total'], 2) }}</td>
                        </tr>
                        @foreach ($category['accounts'] as $account)
                            <tr>
                                <td class="indent-account">{{ $account->code ?? '' }} {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->calculateBalanceDate($startDate, $endDate), 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <tfoot>
                    <tr>
                        <th>Total Equity and Liabilities</th>
                        <th class="text-end">{{ number_format($totalLiabilities + $totalEquity, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <h2>Summary</h2>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Total Assets:</strong> {{ number_format($totalAssets, 2) }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Total Equity and Liabilities:</strong> {{ number_format($totalLiabilities + $totalEquity, 2) }}</p>
        </div>
    </div>
    @if ($totalAssets !== ($totalLiabilities + $totalEquity))
        <div class="alert alert-warning" style="color: black">
            <strong>Warning:</strong> The balance sheet is not balanced! Total Assets ({{ number_format($totalAssets, 2) }}) does not equal Total Liabilities ({{ number_format($totalLiabilities, 2) }}) + Equity ({{ number_format($totalEquity, 2) }}).
            <br>
            <strong>Discrepancies Found:</strong>
            <ul>
                @foreach ($discrepancies as $discrepancy)
                    <li>{{ $discrepancy }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-success" style="color: black">
            <strong>Success:</strong> The balance sheet is balanced!
        </div>
    @endif


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

            $('#clear-filters').on('click', function() {
                $('#date_range').val('');
                $('#search-form').submit();
            });
        });
    </script>
@endsection
