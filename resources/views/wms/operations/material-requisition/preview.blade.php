<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MRF ISSUE</title>
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
            margin-top: 5px;
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
        .company_name{
            font-size: 15px;
            margin-left: 5px !important;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .company_num{
            font-size: 15px;
            margin-left: 30px;
            margin-top: 0px;
        }
        .border-gapped {
            border-collapse: collapse; /* Removes the gap between the table cells */
            width: 100%; /* Optional: Set the width of the table */
            margin: 0 auto; /* Centers the table horizontally */
        }

        .border-gapped td {
            border: 2px solid gray;
            padding: 10px; /* Optional: Adjust padding inside cells */
        }

        .centered-table {
            text-align: center; /* Centers the text inside the table */
        }
        .tdwd{
            width: 25px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <img src="{{ public_path('assets/images/zenig1.png') }}" alt="Zenig Auto Logo">
                    </td>
                    <td>
                        <h1>MRF ISSUED FORM</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="company_name">ZENIG AUTO SDN BHD</p>
                        <p class="company_num">(1015897-M)</p>
                    </td>
                </tr>
            </table>
        </div>
        <table class="border-gapped">
            <tr>
                <td>MRF NO:</td>
                <td>{{ $material_requisition->ref_no }}</td>
                <td>Date Plan:</td>
                <td>{{ $material_requisition->plan_date }}</td>
            </tr>
            <tr>
                <td>SHIFT:</td>
                <td colspan="3">{{ $material_requisition->shift }}</td>
            </tr>
            <tr>
                <td>MACHINE / LINE NO.</td>
                <td colspan="3">({{ $machine->code }}) {{ $machine->name }}</td>
            </tr>
            <tr>
                <td>QUANTITY / PIECES / BAG</td>
                <td colspan="3">{{ $material_requisition_details->request_qty }}</td>
            </tr>
            <tr>
                <td>MATERIAL / C.PART NAME</td>
                <td colspan="3">({{ $product->part_no }}) {{ $product->part_name }}</td>
            </tr>
            <tr>
                <td>LOT NO.</td>
                <td colspan="3"></td>
            </tr>
        </table>
        <center><h1>ISSUER</h1></center>
        <table class="border-gapped">
            <tr>
                <td style="width: 35px">DATE:</td>
                <td>     </td>
                <td style="width: 25px">Time: </td>
                <td>     </td>
            </tr>
            <tr>
                <td>ISSUE BY:</td>
                <td colspan="3">{{ $material_requisition->shift }}</td>
            </tr>
            <tr>
                <td>ISSUE DAY:</td>
                <td colspan="3">{{ date( 'l', strtotime($material_requisition->issue_date)) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
