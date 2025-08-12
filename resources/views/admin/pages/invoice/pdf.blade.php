<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Quotation</title>
    <style>
        @page {
            margin: 1rem;
        }

        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            background-color: #ffffff;
        }

        .invoice-header {
            padding: 20px;
            background-color: #333333;
            color: #ffffff;
        }

        .invoice-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-header td {
            vertical-align: middle;
        }

        .invoice-header img {
            max-width: 150px;
            height: auto;
        }

        .invoice-header h1 {
            font-size: 28px;
            margin: 0;
            text-align: right;
        }

        .invoice-header .contact-info {
            font-size: 11px;
            margin-top: 10px;
            text-align: right;
        }

        .invoice-section {
            margin-top: 10px;
        }

        .section-title {
            font-size: 13px;
            color: #333333;
            border-bottom: 2px solid #4a4a4a;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .invoice-details,
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details td {
            padding: 5px;
            vertical-align: top;
            line-height: 12px;
        }

        .invoice-details .label {
            font-weight: bold;
            background-color: #f4f4f4;
            width: 90px;
        }

        .invoice-details td,
        .invoice-items th,
        .invoice-items td {
            padding: 5px;
            font-size: 11px;
        }

        .invoice-items th {
            background-color: #f4f4f4;
            text-align: right;
        }

        .invoice-items th:first-child,
        .invoice-items td:first-child {
            text-align: left;
        }

        .invoice-total {
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            border-top: 2px solid #4a4a4a;
            padding-top: 10px;
        }

        .total {
            border-top: 1px solid #d1d1d1;
        }

        .specs,
        .tnc {
            font-size: 11px;
            color: #535353;
            line-height: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <table>
                <tr>
                    <td>
                        <img src="{{ public_path('assets/frontend/assets/logo.png') }}" alt="Sakura Motors" />
                    </td>
                    <td>
                        <h1>Proforma Invoice</h1>
                        <div class="contact-info">
                            305-0818, 3-48-48, Gakuen Minami, Tsukuba Shi, Ibaraki Ken, Japan. <br>
                            Tel: +81-29-819-0850 | Fax: +81-29-868-3669 | Email: info@sakuramotors.com
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-section">
            <table class="invoice-details">
                <tr>
                    <td style="border: unset">
                        <div class="section-title">Invoice Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Invocie No:</td>
                                <td>SM-{{ $invoice->inquiry->stock_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Issued Date:</td>
                                <td>{{ $invoice->inquirycreated_at ? $invoice->created_at->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Due Date:</td>
                                <td>{{ $invoice->inquiry->reserved_expiry_date ? $invoice->inquiry->reserved_expiry_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Sales Agent Email:</td>
                                <td>{{ $invoice->inquiry->salesAgent && $invoice->inquiry->salesAgent->email ? $invoice->inquiry->salesAgent->email : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Sales Agent Contact:</td>
                                <td>{{ $invoice->inquiry->salesAgent && $invoice->inquiry->salesAgent->phone ? $invoice->inquiry->salesAgent->phone : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Customer Message:</td>
                                <td>
                                    @if ($invoice->inquiry->inqu_comment)
                                        <span style="font-style: italic">"{{ $invoice->inquiry->inqu_comment }}"</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-section">
            <table class="invoice-details">
                <tr>
                    <td width="33.333%" style="border: unset">
                        <div class="section-title">Consignee Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Customer Name:</td>
                                <td>{{ $invoice->inquiry->inqu_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Email Address:</td>
                                <td>{{ $invoice->inquiry->inqu_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Mobile Number:</td>
                                <td>{{ $invoice->inquiry->inqu_mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Address:</td>
                                <td>{{ $invoice->inquiry->inqu_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">City:</td>
                                <td>{{ $invoice->inquiry->inqu_city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Country:</td>
                                <td>{{ $invoice->inquiry->inqu_country && $invoice->inquiry->inquiryCountry->country ? $invoice->inquiry->inquiryCountry->country: 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.333%" style="border: unset">
                        <div class="section-title">Notify Party</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Customer Name:</td>
                                <td>{{ $invoice->inquiry->inqu_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Email Address:</td>
                                <td>{{ $invoice->inquiry->inqu_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Mobile Number:</td>
                                <td>{{ $invoice->inquiry->inqu_mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Address:</td>
                                <td>{{ $invoice->inquiry->inqu_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">City:</td>
                                <td>{{ $invoice->inquiry->inqu_city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Country:</td>
                                <td>{{ $invoice->inquiry->inqu_country && $invoice->inquiry->inquiryCountry->country ? $invoice->inquiry->inquiryCountry->country: 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.333%" style="border: unset">
                        <div class="section-title">Shipping Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Final Country:</td>
                                <td>{{ $invoice->inquiry->inqu_country && $invoice->inquiry->inquiryCountry->country ? $invoice->inquiry->inquiryCountry->country: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Port Name:</td>
                                <td>{{ $invoice->inquiry->inqu_port ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Type of Purchase:</td>
                                <td>{{ $invoice->inquiry->total_price && $invoice->inquiry->total_price !== 'ASK' ? 'CIF' : 'FOB' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Insurance:</td>
                                <td>{{ $invoice->inquiry->insurance && $invoice->inquiry->insurance !== '0' ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Inspection:</td>
                                <td>{{ $invoice->inquiry->inspection && $invoice->inquiry->inspection !== '0' ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-section" style="padding: 10px">
            <div class="section-title">Items Ordered</div>
            <table class="invoice-items">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right">Price</th>
                        <th style="text-align: right">Qty</th>
                        <th style="text-align: right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            {{ $invoice->inquiry->vehicle_name ?? 'N/A' }}
                            @if($invoice->inquiry->vehicle)
                            <div class="specs">
                                Stock No: {{ $invoice->inquiry->stock_no ?? 'N/A' }} <br>
                                Make: {{ $invoice->inquiry->vehicle->make_type ?? 'N/A' }} <br>
                                Model: {{ $invoice->inquiry->vehicle->model_type ?? 'N/A' }} <br>
                                Year: {{ $invoice->inquiry->vehicle->registration ?? 'N/A' }} <br>
                                Engine Size: {{ $invoice->inquiry->vehicle->engine_size ?? 'N/A' }} <br>
                                Transmission: {{ $invoice->inquiry->vehicle->transmission ?? 'N/A' }} <br>
                                Fuel: {{ $invoice->inquiry->vehicle->fuel_type ?? 'N/A' }} <br>
                            </div>
                            @endif
                        </td>
                        <td style="vertical-align: top;text-align: right">{{ $invoice->inquiry->fob_price ?? 'ASK' }}</td>
                        <td style="vertical-align: top;text-align: right">1</td>
                        <td style="vertical-align: top;text-align: right">{{ $invoice->inquiry->fob_price ?? 'ASK' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="invoice-section">
            <table class="invoice-details">
                <tr>
                    <td width="50%" style="border: unset"></td>
                    <td width="50%" style="border: unset">
                        <table class="invoice-items">
                            <tbody>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        FOB Price
                                    </td>
                                    <td style="text-align: right">${{ number_format($invoice->inquiry->fob_price) ?? 'ASK' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Freight Fee
                                    </td>
                                    <td style="text-align: right">${{ number_format($invoice->inquiry->freight_fee) ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Insurance Fee
                                    </td>
                                    <td style="text-align: right">${{ number_format($invoice->inquiry->insurance) ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Inspection Fee
                                    </td>
                                    <td style="text-align: right">${{ $invoice->inquiry->inspection ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Discount
                                    </td>
                                    <td style="text-align: right">(${{ number_format($invoice->inquiry->discount) ?? '0' }})</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold" class="total">
                                        Total Amount
                                    </td>
                                    <td style="text-align: right; font-weight: bold" class="total">
                                        ${{ number_format($invoice->inquiry->total_price) ?? 'ASK' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Amount Paid
                                    </td>
                                    <td style="text-align: right; font-weight: bold ">${{ number_format($invoice->total_paid ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold" class="total">
                                        Amount Due
                                    </td>
                                    <td style="text-align: right; font-weight: bold" class="total">
                                        ${{ number_format($invoice->total_paid) ? number_format($invoice->inquiry->total_price - $invoice->total_paid) : '0' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div style="page-break-after: always;"></div>

        @if ($invoice->billingHistory->count() > 0)
            <div class="invoice-section" style="padding: 10px">
                <div class="section-title">Billing History</div>
                <table class="invoice-items">
                    <thead>
                        <tr>
                            <th style="text-align: left">#</th>
                            <th style="text-align: left">Created Date</th>
                            <th style="text-align: left">Description</th>
                            <th style="text-align: right">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->billingHistory as $key => $billing)
                            <tr>
                                <td style="text-align: left">{{ $key + 1 }}.</td>
                                <td style="text-align: left">{{ $billing->created_at->format('M d, Y') }}</td>
                                <td style="text-align: left; white-space: normal;">{{ $billing->description ?? 'N/A' }}</td>
                                <td style="vertical-align: top;text-align: right">${{ number_format($billing->paid_amount, 2) }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2" class="total"></td>
                            <td style="vertical-align: top;text-align: right;font-weight: bold" class="total">
                                Total Amount Paid
                                @if (number_format($invoice->inquiry->total_price - $invoice->total_paid) == 0)
                                    (Verified)
                                @endif
                            </td>
                            <td style="vertical-align: top;text-align: right;font-weight: bold" class="total">{{ number_format($invoice->total_paid) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <div class="invoice-section">
            <table class="invoice-details">
                <tr>
                    <td>
                        <div class="section-title">Bank Account & Beneficiary Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Account Name:</td>
                                <td>{{ config('app.account_name', 'SAKURA MOTORS CO.,LTD') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Bank Name:</td>
                                <td>{{ config('app.bank_name', 'SUMITOMO MITSUI BANKING CORPORATION') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Branch Name:</td>
                                <td>{{ config('app.branch_name', 'TSUKUBA BRANCH') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Branch Address:</td>
                                <td>{{ config('app.branch_address', '5-19, KENKYUGAKUEN, TSUKUBA-SHI, IBARAKI ,305-0817, JAPAN') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Swift Code:</td>
                                <td>{{ config('app.swift_code', 'SMBCJPJT') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Account Number:</td>
                                <td>{{ config('app.account_number', '244-0488651') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-section">
            <table class="invoice-details">
                <tr>
                    <td>
                        <div class="section-title">Terms & Conditions</div>
                        <div class="tnc">
                           1) The vehicle will be ONLY reserved for 72 hours from time of issuance the proforma Invoice and purchase agreement.<br>
                            2) Share the proof of payment (TT slip or any proof of payment with bank stamp) for the reserved vehicle with SAKURA MOTORS within 72 hours.<br>
                            3) All bank charges and handling fees of the payment to be borne by the Purchaser (You).<br>
                            4) Ensure to use a same E-MAIL Address and TELEPHONE number to avoid any mistakes and delays during the purchase.<br>
                            5) Beware of scams, forge emails & websites pretending to be or represent Sakura Motors.<br>
                            6) Always make sure to double-check the bank details with the sales agent/Sakura Motors before you make any payment.<br>
                            7) There will be a penalty (USD500) imposed for order cancelation upon full/partly payment for the vehicle purchase.<br>
                            8) Vehicle purchases with a pre-ship inspection; the inspection fee will be forfeited.<br>
                            9) Vehicle for shipment, will only be proceeded after settling the full payment.<br>
                            10) All used vehicles have NO warranty.<br>
                            11) Make sure to read, understand and agree to all the terms & conditions mentioned above before you purchase the vehicle<br>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 11px; color: #666;">
            <p>Thank you for your interest in Sakura Motors!</p>
            <p>Generated on: {{ date('F d, Y') }}</p>
        </div>
    </div>
</body>

</html>




