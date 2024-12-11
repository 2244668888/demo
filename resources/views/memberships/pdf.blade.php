<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
        }

        .header img {
            width: 175px;
            display: block;
            margin: 0 auto;
        }

        .header h3 {
            margin: 10px 0;
            text-align: center;
        }

        .company-details,
        .supplier-details {
            width: 100%;
            margin-bottom: 20px;
        }

        .company-details td,
        .supplier-details td {
            border: none;
            padding: 5px;
        }

        .details-table,
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td,
        .item-table th,
        .item-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .item-table th,
        .item-table td {
            text-align: center;
        }

        .details-tables {
            width: 100%;
            border-collapse: collapse;
        }

        .details-tables th,
        td {
            border: 1px solid #000;
            text-align: left;
            padding: 5px;
        }

        .pagenum:before {
            content: counter(page);
        }

        .no-border-table td {
            border: none;
        }

        .mt-none {
            margin-top: none;
        }

        .mb-none {
            margin-bottom: none;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .page-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .membership-info {
            margin-bottom: 20px;
        }

        .membership-info p {
            margin: 5px 0;
        }

        .services-list {
            margin: 20px 0;
            list-style-type: none;
            padding-left: 0;
        }

        .services-list li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

    </style>
</head>
<body>
    <div class="membership-info">
        <h1>Membership Details</h1>
        <p><strong>Member Name:</strong> {{ $membership->member_name }}</p>
        <p><strong>Phone:</strong> {{ $membership->phone }}</p>
        <p><strong>Issue Date:</strong> {{ $membership->issue_date }}</p>
        <p><strong>Expiry Date:</strong> {{ $membership->expiry_date }}</p>
    </div>

    <div class="services-info">
        <h2>Selected Services</h2>
        <ul class="services-list">
            @foreach ($membership->services as $service)
                <li>{{ $service->name }} - {{ $service->pivot->price }} PKR</li>
            @endforeach
        </ul>
    </div>

</body>
</html>
