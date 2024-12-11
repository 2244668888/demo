<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
        }
        img {
            width: 110px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        .content {
            margin-top: 20px;
            width: 100% !important;
        }

        .content h3 {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .content p {
            margin: 5px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .term-conditions {
            margin-top: 20px;
        }

        .term-conditions h4 {
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 12px;
        }

        .pagenum:before {
            content: counter(page);
        }
        .text-align-right{
            text-align: right !important;
        }
        .myTable-class{
            border: none !important;
            width: 100% !important;
            border-collapse:unset !important;
        }
        .page-break {
            page-break-after: always;
        }
        .fs-12{
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">

    </div>

    <div class="content">
        <table class="myTable-class">
            <tr>
                <td>
                    <p><strong>Ref No:</strong> {{ $quotation->ref_no }}</p><br>
                    <p><strong>{{ $quotation->customers->name }}</strong></p>
                    <p>{{ $quotation->customers->address }}</strong></p><br>
                    <p>Date: {{ Carbon\Carbon::parse($quotation->date)->format('d/m/Y') }}</p><br>
                    <p>
                        <span style="padding-right: 10px;"> Attn. </span>  : {{ $quotation->customers->pic_name }}<br>
                        <span style="padding-left: 60px;">{{ $quotation->customers->pic_department }}</span>
                    </p>
                    <p>
                        <span style="padding-right: 15px;"> CC. </span>  : {{ $quotation->cc }}<br>
                        <span style="padding-left: 60px;">{{ $quotation->department }}</span>
                    </p>
                </td>
                <td class="text-align-right" style="vertical-align: top;">
                    <img src="{{ public_path('assets/images/zenig1.png') }}" alt="Zenig Auto Logo" style="width: 200px">
                    <p class="text-align-right"><b>ZENIG AUTO SDN. BHD.</b></p>
                    <p class="text-align-right"><b>(1015897-M)</b></p>
                </td>
            </tr>
        </table>
        <h3>PART PRICE QUOTATION</h3>


        <table class="table" >
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Part No.</th>
                    <th>Part Name</th>
                    <th>Remark</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation_details as $quotation_detail)
                    <tr>
                        <td class="fs-12">{{ $loop->iteration }}</td>
                        <td class="fs-12">{{ $quotation_detail->products->part_no ?? '' }}</td>
                        <td class="fs-12">{{ $quotation_detail->products->part_name ?? '' }}</td>
                        <td class="fs-12">{{ $quotation_detail->remarks }}</td>
                        <td class="fs-12">{{ number_format($quotation_detail->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="term-conditions" style="margin-bottom: 10px">
            <h4>Term and Conditions:</h4>
            <span >{!! nl2br($quotation->term_conditions) !!}</span>
        </div>
        <p style="margin-bottom: 10px">We trust that our quotation will meet your requirements and expectation. We look forward to your kind
            consideration and approval.</p>

        <p style="margin-bottom: 10px">Thanks and regards,</p>
        <p style="margin-bottom: 10px"><strong>Zenig Auto Sdn. Bhd.</strong></p>
        <p>Name: {{ Auth::user()->user_name }}</p>
        <p>Position: {{ Auth::user()->designation->name ?? '' }}</p>
        <p>Department: {{ Auth::user()->department->name ?? '' }}</p>
        <div class="footer">
            <p>Lot 9414 Jalan Jasmine 1, Seksyen BB10, Bukit Beruntung, 48300 Rawang, Selangor</p>
            <p>Tel: +60(3)60281721/4421 | Fax: +60(3)60282844</p>
            <p>This statement is computer-generated, and no signature is required.</p>
            {{-- <p>Page <span class="pagenum"></span></p> --}}
        </div>
    </div>


</body>

</html>
