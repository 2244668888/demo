<!DOCTYPE html>
<html>
<head>
    <title>Accounts PDF</title>
</head>
<body>
    <h1>Accounts List</h1>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Account Code</th>
                <th>Account Name</th>
                <th>Type</th>
                <th>Opening Balance</th>
                <th>Current Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account->code ?? '' }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->type }}</td>
                <td>{{ number_format($account->opening_balance, 2) }}</td>
                <td>{{ number_format($account->calculateBalance(), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
