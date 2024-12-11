    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>INVOICE</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }




            .header img {
                width: 175px;
            }

            .header h3 {
                margin: 10px 0;
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
            .no-border-table td{
                border: none ;
            }
            .mt-none{
                margin-top: none;
            }
            .mb-none{
                margin-bottom: none;
            }
            .text-center {
                text-align: center !important;
            }
            .text-right{
                text-align: right !important;
            }
            .page-footer {
                position: absolute;
                bottom: 0;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <table class="no-border-table">
                    <tr>
                        <td>
                            <img src="{{ public_path('assets/images/zenig1.png') }}" alt="Zenig Auto Logo">
                        </td>
                        <td>
                            <h3>ZENIG AUTO SDN BHD</h3>
                            <p class="mt-none mb-none">(1015897-M)</p>
                            <p class="mt-none mb-none">Lot 9414, Jalan Jasmine 1, Seksyen BB 10, Bukit Beruntung, 48300 Rawang, Selangor Darul Ehsan.<br>
                                Tel: 03-6028 1712/ 03-6028 4421<br>
                                Fax: 03-6028 2844
                            </p>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="2"><h3 class="text-center">INVOICE</h3></td>
                    </tr>


                </table>
            </div>
            <table class="company-details">
                <tr>
                    <td>
                        @if ($invoice->outgoings)
                            @if ($invoice->outgoings->category == 1)
                                {{ $invoice->outgoings->sales_return->customer->name ?? '' }}
                            @elseif($invoice->outgoings->category == 2)
                                {{ $invoice->outgoings->purchase_return->supplier->name ?? '' }}
                            @elseif($invoice->outgoings->category == 3)
                                {{ $invoice->outgoings->order->customers->name ?? '' }}
                            @endif
                        @endif
                    </td>
                    <td></td>
                    <td class="text-right">NO.:</td>
                    <td>{{ $invoice->invoice_no }}</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="3" style="vertical-align: text-top;">
                        @if ($invoice->outgoings)
                            @if ($invoice->outgoings->category == 1)
                                {{ $invoice->outgoings->sales_return->customer->address ?? '' }}
                            @elseif($invoice->outgoings->category == 2)
                                {{ $invoice->outgoings->purchase_return->supplier->address ?? '' }}
                            @elseif($invoice->outgoings->category == 3)
                                {{ $invoice->outgoings->order->customers->address ?? '' }}
                            @endif
                        @endif
                    </td>
                    <td class="text-right">DATE:</td>
                    <td>{{ $invoice->date }}</td>
                </tr>
                <tr>
                    <td class="text-right">TERM:</td>
                    <td>{{ $invoice->term }}</td>
                </tr>
                <tr>
                    <td class="text-right">PAGE:</td>
                    <td><span class="pagenum"></span></p>
                    </td>
                </tr>
                <tr>
                    <td>ATTN:</td>
                    <td>
                        @if ($invoice->outgoings)
                            @if ($invoice->outgoings->category == 1)
                                {{ $invoice->outgoings->sales_return->customer->pic_name ?? '' }}
                            @elseif($invoice->outgoings->category == 2)
                                {{ $invoice->outgoings->purchase_return->supplier->contact_person_name ?? '' }}
                            @elseif($invoice->outgoings->category == 3)
                                {{ $invoice->outgoings->order->customers->pic_name ?? '' }}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>TEL:</td>
                    <td>
                        @if ($invoice->outgoings)
                            @if ($invoice->outgoings->category == 1)
                                {{ $invoice->outgoings->sales_return->customer->phone ?? '' }}
                            @elseif($invoice->outgoings->category == 2)
                                {{ $invoice->outgoings->purchase_return->supplier->contact_person_telephone ?? '' }}
                            @elseif($invoice->outgoings->category == 3)
                                {{ $invoice->outgoings->order->customers->phone ?? '' }}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>FAX:</td>
                    <td>
                        @if ($invoice->outgoings)
                            @if ($invoice->outgoings->category == 1)
                                {{ $invoice->outgoings->sales_return->customer->pic_fax ?? '' }}
                            @elseif($invoice->outgoings->category == 2)
                                {{ $invoice->outgoings->purchase_return->supplier->contact_person_fax ?? '' }}
                            @elseif($invoice->outgoings->category == 3)
                                {{ $invoice->outgoings->order->customers->pic_fax ?? '' }}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>GST NO:</td>
                    <td>{{ $invoice->outgoings->ref_no ?? '' }}</td>
                </tr>
                <tr>
                    <td>ACC NO:</td>
                    <td>{{ $invoice->acc_no }}</td>
                    <td>DO NO:</td>
                    <td>{{ $invoice->outgoings->ref_no ?? '' }}</td>
                </tr>
            </table>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Part No</th>
                        <th>Part Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Disc</th>
                        <th>Excl. SST</th>
                        <th>SST</th>
                        <th>Incl. SST</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($invoice_details as $invoice_detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice_detail->product->part_no }}
                            </td>
                            <td>{{ $invoice_detail->product->part_name }}</td>
                            <td>{{ $invoice_detail->product->units->name ?? '' }}</td>
                            <td>{{ $invoice_detail->qty }}</td>
                            <td>{{ $invoice_detail->price }}
                            </td>
                            <td>{{ $invoice_detail->disc }}
                            </td>
                            <td>{{ $invoice_detail->excl_sst }}</td>
                            <td>{{ $invoice_detail->sst }}</td>
                            <td>{{ $invoice_detail->incl_sst }}</td>
                            @php
                                $total += $invoice_detail->qty * $invoice_detail->price;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="page-footer">
                RINGGIT MALAYSIA:{{$incl_sst_words}} only
                <table class="details-tables">
                    <thead>
                        <tr>
                            <th>SST Summary</th>
                            <th>Amount</th>
                            <th>SST Amount</th>
                            <th colspan="2" style="text-align: right;">MYR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php
                                $percentage_number = $sst_summary->sst_percentage/100;
                                $sst_amount = $total * $percentage_number;
                            @endphp
                            <td></td>
                            <td>{{ $sst_summary->sst_percentage ?? '' }}% </td>
                            <td>{{ $sst_amount }}</td>
                            <th>Sub Total</th>
                            <td style="width: 100px;">{{ $total }}</td>
                        </tr>
                        <tr>
                            <td rowspan="4" colspan="3" style="border: none;"></td>
                            <th>Total Discount</th>
                            <td>{{ $disc }}</td>
                        </tr>
                        <tr>
                            <th>Total Excl. SST</th>
                            <td>{{ $excl_sst }}</td>
                        </tr>
                        <tr>
                            <th>Add SST</th>
                            <td>{{ $sst }}</td>
                        </tr>
                        <tr>
                            <th>Total Payable Incl. SST</th>
                            <td>{{ $incl_sst }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <p class="mb-none">*This is computer generated document. No Signature is required.</p>
                    <p class="mt-none mb-none"><b>THANK YOU</b></p>
                </div>
            </div>
        </div>
    </body>

    </html>
