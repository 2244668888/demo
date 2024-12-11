<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOOD RETURN DOCUMENT (GRD)</title>
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
        .supplier-details {
            width: 100%;
            margin-bottom: 20px;
        }

        .company-details td,
        .supplier-details td {
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

        .content-before-footer {
            min-height: 300px;
        }

        .footer {
            width: 100%;
            overflow-x: auto;
            page-break-before: auto;
        }

        .footer td {
            padding: 5px;
            border: 1px solid #000;
        }

        @page {
            margin: 20mm;
        }

        .footer-container {
            page-break-inside: avoid;
            page-break-before: auto;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-before-footer">
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
            <table class="company-details">
                <tr>
                    <td>TO:</td>
                    <td>{{ $purchase_return->supplier->name ?? '' }}</td>
                    <td>GRD NO:</td>
                    <td>{{ $purchase_return->grd_no }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ $purchase_return->supplier->address ?? '' }}</td>
                    <td>DATE:</td>
                    <td>{{ $purchase_return->date }}</td>
                </tr>
                <tr>
                    <td>ATTN:</td>
                    <td>{{ $purchase_return->supplier->contact_person_name ?? '' }}</td>
                    <td style="border: 1px solid black;">FOR OFFICE USE</td>
                    <td style="border: 1px solid black;">
                        @if ($purchase_return->for_office == 'For Office Use')
                            <img src="{{ asset('assets/images/check.png') }}" alt="Zenig Auto Logo" width="20">
                        @else
                            <img src="{{ asset('assets/images/uncheck.png') }}" alt="Zenig Auto Logo" width="15">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>TEL:</td>
                    <td>{{ $purchase_return->supplier->contact_person_telephone ?? '' }}</td>
                    <td style="border: 1px solid black;">RETURN FOR CREDIT</td>
                    <td style="border: 1px solid black;">
                        @if ($purchase_return->for_office == 'Return For Credit')
                            <img src="{{ asset('assets/images/check.png') }}" alt="Zenig Auto Logo" width="20">
                        @else
                            <img src="{{ asset('assets/images/uncheck.png') }}" alt="Zenig Auto Logo" width="15">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>FAX:</td>
                    <td>{{ $purchase_return->supplier->contact_person_fax ?? '' }}</td>
                    <td style="border: 1px solid black;">RETURN FOR REPLACEMENT</td>
                    <td style="border: 1px solid black;">
                        @if ($purchase_return->for_office == 'Return For Replacement')
                            <img src="{{ asset('assets/images/check.png') }}" alt="Zenig Auto Logo" width="20">
                        @else
                            <img src="{{ asset('assets/images/uncheck.png') }}" alt="Zenig Auto Logo" width="15">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>REF NO:</td>
                    <td>{{ $purchase_return->purchase_order->ref_no ?? '' }}</td>
                    <td style="border: 1px solid black;">GOOD LOAD RETURN</td>
                    <td style="border: 1px solid black;">
                        @if ($purchase_return->for_office == 'Good Loan Return')
                            <img src="{{ asset('assets/images/check.png') }}" alt="Zenig Auto Logo" width="20">
                        @else
                            <img src="{{ asset('assets/images/uncheck.png') }}" alt="Zenig Auto Logo" width="15">
                        @endif
                    </td>
                </tr>
            </table>
            <table class="item-table">
                <thead>
                    <tr>
                        <th rowspan="2">Item</th>
                        <th rowspan="2">Part No</th>
                        <th rowspan="2">Part Name</th>
                        <th rowspan="2">Qty</th>
                        <th colspan="4">Good Inward</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>No.</th>
                        <th>Bal.</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase_return_products as $purchase_return_product)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $purchase_return_product->product->part_no ?? '' }}
                            </td>
                            <td>
                                {{ $purchase_return_product->product->part_name ?? '' }}
                            </td>
                            <td>
                                {{ $purchase_return_product->return_qty }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="details-table" style="margin-bottom: 0;">
                <tr>
                    <td style="width: 200px;">Finance Approval:</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Security Release:</td>
                    <td></td>
                </tr>
            </table>
            <table class="details-table" style="margin-top: 0;">
                <tr>
                    <td style="border-top: 0; width: 189px;">Date:</td>
                    <td style="border-top: 0; width: 150px;"></td>
                    <td style="border-top: 0; width: 200px;">Time:</td>
                    <td style="border-top: 0; width: 150px;"></td>
                </tr>
            </table>
            <p>Kind acknowledge receipt of the following goods by signing to us the duplicate of this form</p>
        </div>
        <div class="footer-container">
            <table class="footer">
                <tr>
                    <td>Remarks:</td>
                    <td colspan="3">{{ $purchase_return->remarks }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Checked By:</td>
                    <td>{{ $purchase_return->checked_bye->user_name ?? '' }}</td>
                    <td style="width: 180px;">Transport By:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Received By:</td>
                    <td>{{ $purchase_return->receive_bye->user_name ?? '' }}</td>
                    <td>Reg. No.:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td></td>
                    <td>Date:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>I/C No.:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Signature:</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
