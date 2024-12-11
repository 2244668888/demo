<!DOCTYPE html>
<html>
<head>
    <title>Ledger PDF</title>
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
        <h3>Single Ledger Statement</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tran) 
                <tr>
                    <td>{{ $tran->created_at->format('Y-m-d') }}</td>
                    <td>{{ $tran->description }}</td>
                    <td>{{ $tran->type == 'debit' ? $tran->amount : '' }}</td>
                    <td>{{ $tran->type == 'credit' ? $tran->amount : '' }}</td>
                    <td>
                        @if (in_array($tran->account->type, ['liability', 'income', 'equity']))
                            {{ $tran->balance >= 0 ? $tran->balance : -$tran->balance }}
                        @else
                            {{ $tran->balance }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
