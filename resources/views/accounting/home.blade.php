@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Accounts Overview</h4>
                </div>
                <div class="card-body" style="height: 418px; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Current Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $account->name }}</td>
                                    <td>
                                        @php
                                            $balance = $account->calculateBalance();
                                            $type = $account->type;
                                            
                                            if (in_array($type, ['liability', 'equity', 'income'])) {
                                                echo ($balance < 0) ? ($balance . ' (DR)') : ($balance . ' (CR)');
                                            } else {
                                                echo ($balance < 0) ? ($balance . ' (CR)') : ($balance . ' (DR)');
                                            }
                                        @endphp
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Accounting Data</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="accountingPieChart" style="height: 418px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Today's Income & Expenses</h4>
                </div>
                <div class="card-body">
                    <p><strong>Today's Total Income:</strong> {{ $todayIncome }}</p>
                    <p><strong>Today's Total Expenses:</strong> {{ $todayExpenses }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Monthly Income & Expenses</h4>
                </div>
                <div class="card-body">
                    <p><strong>This Month's Total Income:</strong> {{ $monthlyIncome }}</p>
                    <p><strong>This Month's Total Expenses:</strong> {{ $monthlyExpenses }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Monthly Income vs Expenses</h4>
                </div>
                <div class="card-body">
                    <canvas id="monthlyIncomeExpenseChart" style="height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('accountingPieChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Total Expenses', 'Total Income', 'Total Assets', 'Total Liabilities & Equity'],
            datasets: [{
                label: 'Balance Summary',
                data: [{{ $totalExpenses }}, {{ $totalIncome }}, {{ $totalAssets }}, {{ $totalLiabilitiesEquity }}],
                backgroundColor: [
                    '#e57373',
                    '#ffd54f',
                    '#64b5f6',
                    '#81c784'
                ],
                borderColor: [
                    '#e57373',
                    '#ffd54f',
                    '#64b5f6',
                    '#81c784'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    var daysInMonth = @json($daysInMonth);
    var ctxLine = document.getElementById('monthlyIncomeExpenseChart').getContext('2d');
    var monthName = '{{ \Carbon\Carbon::now()->format("F") }}';
    var year = '{{ \Carbon\Carbon::now()->format("Y") }}'; 
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: Array.from({length: daysInMonth}, (_, i) => i + 1),
            datasets: [
                {
                    label: 'Income',
                    data: @json($monthlyIncomeData), 
                    borderColor: '#4caf50',
                    fill: false,
                },
                {
                    label: 'Expenses',
                    data: @json($monthlyExpensesData),
                    borderColor: '#f44336',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `${monthName} ${year}`
                }
            }
        }
    });
</script>


@endsection
