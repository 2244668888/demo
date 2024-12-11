<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance Report</title>
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
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
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
        <h3>Trial Balance Report</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Type</th>
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
                        <td class="{{ $account['category'] ? 'indent' : '' }}">{{ $account['account']->name }}</td>
                        <td>Ledger</td>
                        <td>{{ $account['debit_total'] > 0 ? 'Dr ' . number_format($account['debit_total'], 2) : '-' }}</td>
                        <td>{{ $account['credit_total'] > 0 ? 'Cr ' . number_format($account['credit_total'], 2) : '-' }}</td>
                        <td>{{ $account['closing_balance'] > 0 ? 'Dr ' . number_format($account['closing_balance'], 2) : 'Cr ' . number_format(abs($account['closing_balance']), 2) }}</td>
                    </tr>
                    @php $previousCategory = $account['category']; @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
