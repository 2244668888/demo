<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profit and Loss Report</title>
    <link rel="shortcut icon" href="https://zenig.iiotmachine.com/assets/images/zenig.png" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            margin-bottom: 60px;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }
        .header .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header .logo {
            max-width: 150px; /* Adjust as needed */
            margin-right: 10px;
        }
        .header .company-details {
            margin-top: 10px;
            font-size: 14px;
            text-align: left;
            font-weight: bold;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        thead th {
            text-align: left;
            font-size: 14px !important;
            padding: 0px 4px 4px 0px;
        }
        tbody td {
            padding: 0px 4px 4px 0px;
        }

        /* Style for amount column */
        .amount-td {
            text-align: right;
        }

        /* No border for non-amount columns */
        .summary-td {
            text-align: left !important;
            border-bottom: none;
        }

        .category {
            background-color: #e6e6e6;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid black;
            font-size: 14px !important;
        }

        /* Remove border for non-amount columns */
        td {
            border-bottom: none;
        }
        .account-name {
            font-size: 12px;
        }
    </style>
</head>
<body>
    @php
        $currentYear = date('Y');
    @endphp
     <div class="header">
        <div class="logo-container">
            <a href="" style="display: flex; align-items: center;">
                <img src="https://zenig.iiotmachine.com/assets/images/zenig1.png" class="logo" alt="ZENIG AUTO" />
            </a>
        </div>
        <div class="company-details">
            <p>ZENIG AUTO SDN BHD (1015897-M)<br>
            Lot 9414, Jalan Jasmine 1, Seksyen BB 10, Bukit Beruntung, 48300<br>
            Rawang, Selangor Darul Ehsan.<br>
            Tel: 03-6028 1712 / 03-6028 4421<br>
            Fax: 03-6028 2844</p>
        </div>
        <h3>Profit and Loss (P&L) Statement</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th class="amount-td">({{$currentYear}})</th>
            </tr>
            <tr>
                <th>Income</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($income as $account)
                <tr>
                    <td class="summary-td account-name">{{ $account->name }}</td>
                    <td class="amount-td account-name">{{ number_format($account->calculateBalance(), 2) }} MYR</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td><strong>Total Incomes</strong></td>
                <td class="amount-td"><strong>{{ number_format($totalIncome, 2) }} MYR</strong></td>
            </tr>
        </tfoot>
    </table>

    <table>
        <tbody>
            <tr>
                <td class="summary-td account-name">Cost of Goods Sold</td>
                <td class="amount-td account-name">{{ number_format($cogs, 2) }} MYR</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total">
                <td><strong>Gross Profit</strong></td>
                <td class="amount-td">
                    <strong>
                        @php
                            $grossProfit = $totalIncome - $cogs;
                        @endphp
                        {{ $grossProfit < 0 ? '(' . number_format(abs($grossProfit), 2) . ')' : number_format($grossProfit, 2) }} MYR
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>
    <table>
        <thead>
            <tr>
                <th>Expense</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $account)
                <tr>
                    <td class="summary-td account-name">{{ $account->name }}</td>
                    <td class="amount-td account-name">{{ number_format($account->calculateBalance(), 2) }} MYR</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td><strong>Total Expenses</strong></td>
                <td class="amount-td"><strong>{{ number_format($totalExpenses, 2) }} MYR</strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Summary</h2>
    <table>
        <tbody>
            <tr>
            <tr>
                <td class="summary-td"><strong>Net Profit (or Loss)</strong></td>
                <td class="amount-td">
                    <strong>
                        @php
                            $netProfitOrLoss = $totalIncome - $totalExpenses;
                        @endphp
                        {{ $netProfitOrLoss < 0 ? '(' . number_format(abs($netProfitOrLoss), 2) . ')' : number_format($netProfitOrLoss, 2) }} MYR
                    </strong>
                </td>
            </tr>            
            <tr>
                <td class="summary-td"><strong>Retained Earnings B/F</strong></td>
                <td class="amount-td">
                    <strong>
                        {{ $carryforwardBalance < 0 ? '(' . number_format(abs($carryforwardBalance), 2) . ')' : number_format($carryforwardBalance, 2) }} MYR
                    </strong>
                </td>
            </tr>
            <tr>
                <td class="summary-td"><strong>Retained Earnings C/F</strong></td>
                <td class="amount-td">
                    <strong>
                        @php
                            $retainedEarningsCF = $totalIncome - $totalExpenses + $carryforwardBalance;
                        @endphp
                        {{ $retainedEarningsCF < 0 ? '(' . number_format(abs($retainedEarningsCF), 2) . ')' : number_format($retainedEarningsCF, 2) }} MYR
                    </strong>
                </td>
            </tr>
        </tbody>
    </table> 
</body>
</html>
