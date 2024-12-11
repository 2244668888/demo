<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DELIVERY ORDER</title>
    <style>
        html,
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            box-sizing: border-box;
        }

        .header-table {
            border-spacing: 0px;
            margin-bottom: 40px;
            box-sizing: border-box;
        }

        .header img {
            width: 170px;
            margin-top: 40px;
            margin-right: 20px;
        }

        .company-details,
        .recipient-details,
        .order-details {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .company-details td,
        .recipient-details td,
        .order-details td {
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
            text-align: center;
        }

        .footer {
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .footer p {
            font-size: 12px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            padding: 5px;
            border: none;
            vertical-align: top;
        }

        .sign-section {
            border-top: 1px solid #000;
            padding-top: 10px;
            width: 30%;
        }

        .sign-section p {
            margin: 0;
        }

        .carrier,
        .customer {
            border-top: 1px solid #000;
            padding-top: 10px;
            width: 35%;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td><img src="{{ public_path('assets/images/zenig1.png') }}" alt="Zenig Auto Logo"></td>
                    <td>
                        <h3>ZENIG AUTO SDN BHD</h3>
                        (1015897-M)<br>
                        Lot 9414, Jalan Jasmine 1, Seksyen BB 10, Bukit Beruntung,<br>
                        48300 Rawang, Selangor Darul Ehsan.<br>
                        Tel: 03-6028 1712/ 03-6028 4421<br>
                        Fax: 03-6028 2844
                    </td>
                    <td style="text-align: right; vertical-align: text-top;">
                        <p>ZA-SCM-FRM-001</p>
                    </td>
                </tr>
            </table>
        </div>

        <table class="recipient-details">
            <tr>
                <td>To:</td>
                <td>
                    @if ($outgoing->category == 1)
                        {{ $outgoing->sales_return->customer->address ?? '' }}
                    @elseif($outgoing->category == 2)
                        {{ $outgoing->purchase_return->supplier->address ?? '' }}
                    @elseif($outgoing->category == 3)
                        {{ $outgoing->order->customers->address ?? '' }}
                    @endif
                </td>
                <td>Delivery Order:</td>
                <td>
                    {{ $outgoing->ref_no }}
                </td>
            </tr>
            <tr>
                <td rowspan="3">

                </td>
                <td></td>
                <td>Mode of Despatch:</td>
                <td>
                    {{ $outgoing->mode }}
                </td>
            </tr>
        </table>
        <table class="details-table">
            <tr>
                <td>Date</td>
                <td>Customer/Supplier</td>
                <td>A/C No.</td>
                <td>Payment Term</td>
            </tr>
            <tr>
                <td>
                    {{ $outgoing->date }}
                </td>
                <td>
                    @if ($outgoing->category == 1)
                        {{ $outgoing->sales_return->customer->name ?? '' }}
                    @elseif($outgoing->category == 2)
                        {{ $outgoing->purchase_return->supplier->name ?? '' }}
                    @elseif($outgoing->category == 3)
                        {{ $outgoing->order->customers->name ?? '' }}
                    @endif
                </td>
                <td>
                    {{ $outgoing->acc_no }}
                </td>
                <td>
                    {{ $outgoing->payment_term }}
                </td>
            </tr>
        </table>
        <table class="item-table">
            <thead>
                <tr>
                    <th>Item No.</th>
                    <th>Part No.</th>
                    <th>Part Name</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outgoing_details as $outgoing_detail)
                    <tr>
                        <td style="width: 70px;">{{ $loop->iteration }}</td>
                        <td>{{ $outgoing_detail->product->part_no }}</td>
                        <td style="width: 230px;">{{ $outgoing_detail->product->part_name }}</td>
                        <td>{{ $outgoing_detail->product->units->name ?? '' }}</td>
                        <td>{{ $outgoing_detail->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="sign-section">
                        <p>ZENIG AUTO SDN BHD</p>
                        <p>Authorized by:</p>
                        <p style="margin-top: 117px;">________________________</p>
                    </td>
                    <td class="carrier">
                        <p>CARRIER:-</p>
                        <p>Received The Above Goods In Good Order & Condition</p>
                        <p style="margin-top: 53px;">Received by: ____________________</p>
                        <p>Lorry No: ____________________</p>
                    </td>
                    <td class="customer">
                        <p>CUSTOMER</p>
                        <p>Received The Above Goods In Good Order & Condition</p>
                        <p>_______________________________<br>Customer Sign & Co's Stamp</p>
                        <p>IC No.: ________________________</p>
                        <p>Date: ________________________</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
