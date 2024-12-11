<!DOCTYPE html>
<html>
<head>
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
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
            max-width: 150px; 
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
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        /* Table headers */
        thead th {
            text-align: left;
            font-size: 14px !important;
            padding: 0px 4px 4px 0px;
        }
        /* Asset and Liability names (left-aligned, no borders) */
        tbody td {
            padding: 0px 4px 4px 0px;
        }
        /* Amount column (right-aligned, bottom border only) */
        .amount-td {
            text-align: right;
            padding: 0px 4px 4px 0px;
        }
        /* Total rows styling */
        .total {
            font-weight: bold;
            font-size: 12px !important;
        }

        .total-amount {
            font-weight: bold;
            border-top: 1px solid black;
            font-size: 14px !important;
        }
        .category {
            background-color: #e6e6e6;
        }
        .account-name {
            font-size: 12px;
        }

        .indent {
            padding-left: 20px;
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
        <h3>Balance Sheet</h3>
    </div>

    <table>
        <h4>Assets</h4>
        <thead>
            <tr>
                <th>Non-Current Asset</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nonCurrentAssets as $asset)
                <tr>
                    <td class="name-td account-name">{{  ucfirst($asset->code ?? '') }} {{  ucfirst($asset->name) }}</td>
                    <td class="amount-td account-name">{{ number_format($asset->calculateBalanceDate(), 2) }} MYR</td>
                </tr>
            @endforeach
            <tr class="total">
                <td class="name-td">Total Non-Current Assets</td>
                <td class="amount-td">{{ number_format($nonCurrentAssets->sum(fn($asset) => $asset->calculateBalanceDate()), 2) }} MYR</td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>Current Asset</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($currentAssets as $asset)
                <tr>
                    <td class="name-td account-name">{{ ucfirst($asset->code ?? '') }} {{ ucfirst($asset->name) }}</td>
                    <td class="amount-td account-name">{{ number_format($asset->calculateBalanceDate(), 2) }} MYR</td>
                </tr>
            @endforeach
            <tr class="total">
                <td class="name-td ">Total Current Assets</td>
                <td class="amount-td ">{{ number_format($currentAssets->sum(fn($asset) => $asset->calculateBalanceDate()), 2) }} MYR</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-amount">
                <td class="name-td ">Total Assets</td>
                <td class="amount-td ">{{ number_format($totalAssets, 2) }} MYR</td>
            </tr>
        </tfoot>
    </table>

    <table>
        <h4>Liabilities</h4>
        <thead>
            <tr>
                <th>Non-Current Liabilities</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nonCurrentLiabilities as $liability)
                <tr>
                    <td class="name-td account-name">{{ $liability->name }}</td>
                    <td class="amount-td account-name">{{ number_format($liability->calculateBalanceDate(), 2) }} MYR</td>
                </tr>
            @endforeach
            <tr class="total">
                <td class="name-td">Total Non-Current Liabilities</td>
                <td class="amount-td">{{ number_format($nonCurrentLiabilities->sum(fn($liability) => $liability->calculateBalanceDate()), 2) }} MYR</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Current Liabilities</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($currentLiabilities as $liability)
                <tr>
                    <td class="name-td account-name">{{ $liability->name }}</td>
                    <td class="amount-td account-name">{{ number_format($liability->calculateBalanceDate(), 2) }} MYR</td>
                </tr>
            @endforeach
            <tr class="total">
                <td class="name-td">Total Current Liabilities</td>
                <td class="amount-td">{{ number_format($currentLiabilities->sum(fn($liability) => $liability->calculateBalanceDate()), 2) }} MYR</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-amount">
                <td class="name-td ">Total Liabilities</td>
                <td class="amount-td ">{{ number_format($totalLiabilities, 2) }} MYR</td>
            </tr>
        </tfoot>
    </table>

    <table>
        <thead>
            <tr>
                <th>Equity</th>
                <th class="amount-td">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equity as $equityAccount)
                <tr>
                    <td class="name-td account-name">{{ $equityAccount->name }}</td>
                    <td class="amount-td account-name">{{ number_format($equityAccount->calculateBalanceDate(), 2) }} MYR</td>
                </tr>
            @endforeach
            <tr>
                <td class="name-td account-name">Retained Earnings (Current Period)</td>
                <td class="amount-td ccount-name">{{ number_format($netIncome, 2) }} MYR</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-amount">
                <td class="name-td">Total Equity</td>
                <td class="amount-td">{{ number_format($totalEquity, 2) }} MYR</td>
            </tr>
        </tfoot>
    </table>

    <h2>Summary</h2>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Total Assets:</strong> {{ number_format($totalAssets, 2) }} MYR</p>
        </div>
        <div class="col-md-6">
            <p><strong>Total Liabilities + Equity (including Retained Earnings):</strong> {{ number_format($totalLiabilities + $totalEquity, 2) }} MYR</p>
        </div>
    </div>

    @if ($totalAssets !== ($totalLiabilities + $totalEquity))
        <div class="alert alert-warning">
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

</body>
</html>
