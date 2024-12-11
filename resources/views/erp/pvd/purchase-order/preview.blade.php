<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 200px;
        }

        .header h2,
        .header p {
            margin: 0;
        }

        .content {
            margin: 0 20px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .row div {
            flex: 1;
            padding: 0 10px;
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

        .footer {
            text-align: left;
            font-size: 12px;
            padding: 10px;
            margin-top: 10px;
        }

        .sign-section {
            width: 100%;
            margin-top: 20px;
        }

        .sign-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .sign-section th,
        .sign-section td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

         .content h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #444;
        }

        .first, .second {
            max-width: 48%; 
            padding: 10px;
            border: 1px solid #ddd; 
            box-sizing: border-box;
            font-size: 10px;
        }

        @media (max-width: 768px) {
            .row2 {
                flex-wrap: wrap; 
            }
            .first, .second {
                flex: 1 1 100%; 
                max-width: 100%; 
            }
        }
        .content p {
            margin-bottom: 15px; 
        }

        .fw-bold {
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }

    </style>
</head>

<body>
    <div class="header">
        <table>
            <tbody>
                <tr>
                    <td>
                        <img src="{{ public_path('assets/images/zenig1.png') }}" alt="Company Logo">
                    </td>
                    <td>
                        <h2>ZENIG AUTO SDN. BHD.</h2>
                        <p>(1015897-M)</p>
                        <p>Lot 9414, Jalan Jasmine 1, Seksyen BB10, Bukit Beruntung, 48300 Rawang, Selangor Darul Ehsan</p>
                        <p>Tel: 03-6028 1712 / 03-6028 4421 Fax: 03-6028 2844</p>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="content">
        <h3 style="width: 100%; text-align: center;">PURCHASE ORDER</h3>
        <table class="table">
            <tbody>
                <tr>
                    <td style="border: none !important;">
                        <p><strong>To:</strong> {{ $purchase_order->supplier->name ?? '' }}</p>
                        <p>{{ $purchase_order->supplier->address ?? '' }}</p>
                        <p><strong>ATTN:</strong> {{ $purchase_order->supplier->contact_person_name ?? '' }}</p>
                        <p><strong>TEL:</strong> {{ $purchase_order->supplier->contact_person_telephone ?? '' }}</p>
                        <p><strong>FAX:</strong> {{ $purchase_order->supplier->contact_person_fax ?? '' }}</p>
                    </td>
                    <td style="border: none !important; vertical-align: text-top;">
                        <p><strong>Delivery To:</strong></p>
                        <p>Zenig Auto Sdn. Bhd.</p>
                        <p>Lot 9414, Jalan Jasmine 1</p>
                        <p>Seksyen BB10, Bukit Beruntung</p>
                        <p>48300 Rawang Selangor</p>
                        <p><strong>PO No:</strong> {{ $purchase_order->ref_no }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <p><strong>Payment Term:</strong> {{ $purchase_order->payment_term }}</p>
                        @if ($purchase_order->pp_id != null)
                            <p><strong>PP No:</strong> {{ $purchase_order->purchase_planning->ref_no ?? '' }}</p>
                        @else
                            <p><strong>PR No:</strong> {{ $purchase_order->purchase_requisition->pr_no ?? '' }}</p>
                        @endif
                        <p><strong>Department:</strong> {{ $purchase_order->department->name ?? '' }}</p>
                        <p><strong>Required Date:</strong> {{ $purchase_order->required_date }}</p>
                    </td>
                    <td>
                        <p><strong>Date:</strong> {{ $purchase_order->date }}</p>
                        <p><strong>Important:</strong> {{ $purchase_order->important_note }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Item No.</th>
                    <th>Quantity</th>
                    <th>Part No.</th>
                    <th>Part Name</th>
                    <th>Discount</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase_order_details as $purchase_order_detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase_order_detail->qty }}</td>
                        <td>{{ $purchase_order_detail->product->part_no ?? '' }}</td>
                        <td>{{ $purchase_order_detail->product->part_name ?? '' }}</td>
                        <td>{{ $purchase_order_detail->disc }}</td>
                        <td>{{ $purchase_order_detail->price }}</td>
                        <td>{{ $purchase_order_detail->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="sign-section">
            <table>
                <thead>
                    <tr>
                        <th>Remark</th>
                        <th>Issued By</th>
                        <th>Checked By</th>
                        <th>Verified By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $purchase_order->reason }}</td>
                        <td>{{ $purchase_order->user->user_name ?? '' }}</td>
                        <td>{{ $purchase_order->checked_by ?? '' }}</td>
                        <td>{{ $purchase_order->verify_by ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>N.B 1. Bill/Invoice to be submitted in duplicate for payment.</p>
        <p>2. Zenig Auto Sdn. Bhd. will not be responsible and will not accept bills for amounts other than those shown.</p>
        <p>3. Please notify us immediately if you are unable to ship/deliver as specified.</p>
    </div>

    @if ($include_terms)
        <div class="page-break"></div>
        <div class="content">
            <h2 style="text-align: center;">Terms and Conditions</h2>
            <div class="row2">
                <div class="first">
                    <p>
                        <span class="fw-bold">1. GENERAL. </span> The following Terms and Conditions of Purchase together with
                        any additional or other terms and conditions collectively referred to as the “T&C"
                        issued apply to all orders placed by Zenig Auto Sdn Bhd (ZASB). Unless expressly
                        agreed to by ZASB in writing, any contradictory or additional terms and
                        conditions of the Supplier are null and void of no effect and are superseded and
                        replaced by these T&C
                    </p>
                    <p>
                       <span class="fw-bold">2, ORDER,  </span> Each Order will contain delivery and other instructions necessary for the
                        performance by Supplier. Unless otherwise agreed, Supplier's receipt of each
                        Order shall be confirmed by Supplier in written form and delivered by mail,
                        courier, facsimile or other electronic transmission to ZASB. If no such written
                        confirmation is received from within 3 working days of the order, Supplier is
                        deemed to have accepted the Order and the applicable T&C. ZASB may provide
                        Supplier with a forecast estimating ZASB's requirement of the goods. Supplier,
                        shall not under any circumstances, take or assume such forecast as instruction to
                        manufacture or supply the Goods and ZASB shall not be held liable for any
                        damage or losses which may result from Supplier's decision to make such an
                        assumption. ZASB may at any time and for any reason, cancel the delivery of the
                        Goods or performance of service under an Order, in whole or in part, by notice of
                        change or cancellation to the Supplier, and Supplier shall promptly comply. In an
                        event of such cancellation of Order, and where Supplier has already procured and
                        produced in accordance with ZASB Order, Supplier shall submit within 5 working
                        days, a cancellation claim which clearly itemizes cost of raw materials, WIP and
                        finished Goods together with necessary supporting details and documents. Both
                        parties shall discuss to resolve the matter. No cancellation claims will be accepted
                        after 5 working days from the date of receipt of the notice of cancellation,
                    </p>
        
                     <p>
                        <span class="fw-bold">3. REJECTION AND RETURN OF PURCHASE ITEM,  </span> In the events that the
                        Goods do not pass ZASB's inspection quality control, specification, or
                        testing (non-qualified Goods), ZASB shall inform Suppller accordingly and
                        Supplier shall collect the non-qualified Goods from ZASB and/or the site
                        appointed by ZASB at Supplier's expenses within 2 working days. Supplier shall at
                        the option of ZASB (i) provide the replacement Goods to ZASB and/or the site
                        appointed by ZASB within 3 working days from the receipts of ZASB's notice; ar (ii)
                        Supplier shall issue a credit note to ZASB for those non-qualified Goods and shall
                        be liable and indemnify ZASB for any loss, damage, expenses and liabilities
                        whatsoever arising from any non-qualified Goods. Supplier shall be liable for all
                        non-qualified Goods, handling fee (including without limitation the freight).
                        Suppliers acknowledges and agrees that in so far as any Goods are to be supplied
                        by ZASB to its customers/partners, the specifications, quality and warranties of
                        the Goods supplied by supplier to ZASB under this T&C shall be back to back with
                        the specifications, quality and warranties of the Goods which ZASB is contracted
                        to supply to its customers/partners.
                     </p>
        
                     <p>
                        <span class="fw-bold">4. PAYMENT. </span> Unless otherwise specified in the Order, payment terms shall be net
                        sixty (60) days from ZASB's receipt of Goods and invoices by end month ciosing
                        The price already includes all possible payments, fees, taxes (and stamp duty)
                        and/or other costs. Supplier shall comply with any and all laws, regulations,
                        orders, and requirements in connection with the Goods. ZASB shall have the right
                        to offset any loss, damage, liability or claim, which it may have against Supplier
                        for any payment due or owesing to Supolier. Any others payment terms only
                        accepted if agreed to by ZASB in writing
                     </p>
        
                     <p>
                        <span class="fw-bold">5. GOODS. </span>Supplier shall not change the material, design, supplier, production
        
                        method, production location, samples or other factor that might affect the quality
                        
                        of or specification of the Goods, unless otherwise consented to by ZASB before
                        hand and in writing. Supplier shall offer ZASB the lowest prices and most
                        favourable terms net of any discounts or rebates that it affords or intends to
                        afford to its other customers for similar Goods regardless of quantity purchased
                        
                        In the event of breach of the foregoing in connection with any Goods that ZASB
                        
                        has received, but has not paid for, @ reduction in the purchase price and; (b) wi
                        
                        respect to any Goods paid for by ZASB either by (i) a purchase price credit or at
                        
                        ZASB's option (ii) a set-off against future obligations.
                        
                     </p>
        
                     <p>
                        <span class="fw-bold">6. DELIVERY. </span>Delivery of the Goods shall be in accordance with the agreed
                        delivery terms and delivery date. In the event any Goods are not delivered in
                        accordance with the agreed delivery or other terms, Supplier shall pay ZASB a
                        contractual penalty which is back to back with the contractual penalty applica
                        to ZASB for the supply of such Goods to its custome*/partner, subject to a
                        minimum penalty of 5.0% per week of delay of the total value for the quantity of
                        the Goods not delivered on time or not in accordance with the agreed terms and
                        conditions. Provided further that ZASB reserves the right to cancel, without any
                        penalties or compensation, any delivery (jess) of the Goods which are not
                        provided for in accordance with the specified deadlines, specification or other
                        terms. If any Goods fails to pass the inspection, quality contro! or testing by ZASB,
                        such Goods will be deemed undelivered.
                        
                     </p>
        
                     <p>
                        <span class="fw-bold">6. DELIVERY. </span>Delivery of the Goods shall be in accordance with the agreed
                        delivery terms and delivery date. In the event any Goods are not delivered in
                        accordance with the agreed delivery or other terms, Supplier shall pay ZASB a
                        contractual penalty which is back to back with the contractual penalty applica
                        to ZASB for the supply of such Goods to its custome*/partner, subject to a
                        minimum penalty of 5.0% per week of delay of the total value for the quantity of
                        the Goods not delivered on time or not in accordance with the agreed terms and
                        conditions. Provided further that ZASB reserves the right to cancel, without any
                        penalties or compensation, any delivery (jess) of the Goods which are not
                        provided for in accordance with the specified deadlines, specification or other
                        terms. If any Goods fails to pass the inspection, quality contro! or testing by ZASB,
                        such Goods will be deemed undelivered.
                        
                     </p>
        
                     <p>
                        <span class="fw-bold">7. ASSIGMENT AND SUBCONTRACTING. </span>No part of this order may be assigned,
                        transferred or subcontracted by Supplier without the prior written approval of ZASE's. ZASB's prior consents required for Supplier to any use independent or
                        sub-contractors in connection with the development, production, sales or
                        shipment of Goods ordered, ‘provided always that Supplier remains solely
                        responsible to ZASB for Supplier’s compliance with the terms and conditions of
                        the order.
                        
                     </p>
                </div>
                <div class="second">
                    <p>
                        <span class="fw-bold">8. TITLE AND RISK OF LOSS. </span>No part of this order may be assigned,
                        transferred or subcontracted by Supplier without the prior written approval of ZASE's. ZASB's prior consents required for Supplier to any use independent or
                        sub-contractors in connection with the development, production, sales or
                        shipment of Goods ordered, ‘provided always that Supplier remains solely
                        responsible to ZASB for Supplier’s compliance with the terms and conditions of
                        the order.
                        
                     </p>
        
                     <p>
                        <span class="fw-bold">9. WARRANTIES. </span>Supplier warrants to ZASB and its customers/partners that the
                        Goods shall be new and unused, free of liens, and perform in accordance with
                        their specifications and be free from defects in materials, workmanship and
                        design and error-free for (i) a period of one(1) months from ZASB's receipt of
                        such Goods and deliver to its Customers/Partners, OR (ii) usage of the Goods for
                        a mileage of at least 100,000km(whichever occurs first), unless otherwise agreed
                        in writing. Supplier acknowledges that such warranties are back to back with the
                        warranties ZASB provides for the supply of the corresponding Goods to its
                        Customers/Partners. Goods not meeting this warranty may be returned to
                        Supplier for credit or replacement. If any defect or deficiency comes to ZASB's
                        knowledge during the said warranty period, ZASB shall notify Supplier of the
                        same. Supplier, at its sole expense, shall at ZASB's request promptly repair or
                        replace (and in any event within 3 working days) the defective of deficient Goods.
                        Supplier shall hold ZASB harmless against all claims and shall indemnify ZASB in
                        full against all damages, cost, expenses, ana liabilities whatsoever including cost
                        of any remedial or defensive actions in an event of any breach of any of these
                        warranties or these T&C.
                        
                     </p>
        
                     <p>
                        <span class="fw-bold">10. CONFIDENTIALITY. </span>CONFIDENTIALITY. Supplier shall not, without first obtaining ZASB's written
                        permission, advertise, publish, or disclose the terms, details, or specifications of
                        this Order, the amount of revenue generated or to be generated from this Order,
                        or the fact that is has furnished or has contracted to furnish ZASB with the Goods
                        or this T&C or any technical, business or proprietary information relating to the
                        Goods or ZASB.
                        
                     </p>
                     <p>
                        <span class="fw-bold">11. COMPLIANCE WITH LAWS. </span>Supplier shall comply with all applicable laws
                        concarning the manufactures and distribution of the Goods, and shall ensure that
                        its activities in performance of this T&C comply with all applicable laws and shall
                        not give rise to ZASB being be in violation of any laws, including without limitation
                        import or export laws, security requirements, materials content, packaging
                        regulations and any codes of conduct.
                     </p>
                     <p>
                        <span class="fw-bold">12. ENTIRE AGREEMENT. </span>ASB's purchase order, this T&C and any other order
                        documents constitute the complete agreement between the parties relating to
                        the Order and supersede any prior agreements, understandings, proposals or
                        othe: communications, whether oral or writing, relating to the Order. No changes,
                        or additions to any term set forth in any Order documents will be binding upon
                        ZASB unless agreed to in writing by ZASB.
                     </p>
        
                     <p>
                        <span class="fw-bold">13. ASSIGMENT. </span>upplier shall not assign its rights or obligations under this T&C
                        without the prior written consent of ZASB. ZASB may assign its rights under this
                        TRC to a subsidiary or affiliate or any third party upon written notice to Supplier.
                     </p>
                     <p>
                        <span class="fw-bold">14. WAIVER. </span>No claim or right arising out of the breach of this T&C by Supplier can
                        be discharged by a waiver unless the same is supported in consideration and is in
                        writing signed by ZASB. 
                     </p>
        
                     <p>
                        <span class="fw-bold">15. PHASE OUT. </span>
                        Supplier must continue supply for automotive Goods to ZASB for
        
                        1Oyears after any phase out model subject to declaration by ZASB'S
                        Customer/Partner. Any problem part supply to ZASB due to material or parts
                        shortage area responsibilities by Supplier.
                     </p>
                     <p>
                        <span class="fw-bold">16. TERMINATION. </span>ZASB shall be entitle to be terminate this TAC at any time and
                        without any penalty or exit or other change, by giving (30) days written notice to
                        Supplier and PAMSB shall pay supplier for goods supplied in occordance with
                        these T&C up to date termination.                
                     </p>
                     <p>
                        <span class="fw-bold">17. FORCE MAJEURE. </span>If either party is prevented from performing any obligation
                        here under by reason of fire, explosion, strike, labour disoute, casualty, accident,
                        lack of failure of transportation facilities, flood, war, civil commotion, acts of Gods,
                        any law, order or decree of any government or subdivision there of or any other
                        cause beyond the reasonable control of such party, then such party shall be
                        excused from performance here under to the extent and for the duration of such
                        prevention, provided it first notifies the other party in writing of such prevention
                        
                        In the event that Supplier remains in force majeure for a period in excess of (30)
                        days, ZASB shall have the right to immediately terminate this Agreement and
                        cancel any or all undelivered order(s).          
                     </p>
                </div>
            </div>
        </div>
    @endif
</body>

</html>
