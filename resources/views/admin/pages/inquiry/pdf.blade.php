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
            line-height: 12px;
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
                        <h1>Quotation</h1>
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
                        <div class="section-title">Inquiry Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Stock No:</td>
                                <td>{{ $inquiry->stock_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Inquiry Date:</td>
                                <td>{{ $inquiry->created_at ? $inquiry->created_at->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Quotation ID:</td>
                                <td>SM-{{ $inquiry->id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Customer Message:</td>
                                <td>
                                    @if ($inquiry->inqu_comment)
                                        <span style="font-style: italic">"{{ $inquiry->inqu_comment }}"</span>
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
                                <td>{{ $inquiry->inqu_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Email Address:</td>
                                <td>{{ $inquiry->inqu_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Mobile Number:</td>
                                <td>{{ $inquiry->inqu_mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Address:</td>
                                <td>{{ $inquiry->inqu_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">City:</td>
                                <td>{{ $inquiry->inqu_city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Country:</td>
                                <td>{{ $inquiry->inqu_country ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.333%" style="border: unset">
                        <div class="section-title">Notify Party</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Customer Name:</td>
                                <td>{{ $inquiry->inqu_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Email Address:</td>
                                <td>{{ $inquiry->inqu_email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Mobile Number:</td>
                                <td>{{ $inquiry->inqu_mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Address:</td>
                                <td>{{ $inquiry->inqu_address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">City:</td>
                                <td>{{ $inquiry->inqu_city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Country:</td>
                                <td>{{ $inquiry->inqu_country ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="33.333%" style="border: unset">
                        <div class="section-title">Shipping Details</div>
                        <table class="invoice-details">
                            <tr>
                                <td class="label">Final Country:</td>
                                <td>{{ $inquiry->inqu_country ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Port Name:</td>
                                <td>{{ $inquiry->inqu_port ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Type of Purchase:</td>
                                <td>{{ $inquiry->total_price && $inquiry->total_price !== 'ASK' ? 'CIF' : 'FOB' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Insurance:</td>
                                <td>{{ $inquiry->insurance && $inquiry->insurance !== '0' ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Inspection:</td>
                                <td>{{ $inquiry->inspection && $inquiry->inspection !== '0' ? 'Yes' : 'No' }}</td>
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
                            {{ $inquiry->vehicle_name ?? 'N/A' }}
                            @if($inquiry->vehicle)
                            <div class="specs">
                                Stock No: {{ $inquiry->stock_no ?? 'N/A' }} <br>
                                Make: {{ $inquiry->vehicle->make_type ?? 'N/A' }} <br>
                                Model: {{ $inquiry->vehicle->model_type ?? 'N/A' }} <br>
                                Year: {{ $inquiry->vehicle->registration ?? 'N/A' }} <br>
                                Engine Size: {{ $inquiry->vehicle->engine_size ?? 'N/A' }} <br>
                                Transmission: {{ $inquiry->vehicle->transmission ?? 'N/A' }} <br>
                                Fuel: {{ $inquiry->vehicle->fuel_type ?? 'N/A' }} <br>
                            </div>
                            @endif
                        </td>
                        <td style="vertical-align: top;text-align: right">{{ $inquiry->fob_price ?? 'ASK' }}</td>
                        <td style="vertical-align: top;text-align: right">1</td>
                        <td style="vertical-align: top;text-align: right">{{ $inquiry->fob_price ?? 'ASK' }}</td>
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
                                    <td style="text-align: right">{{ $inquiry->fob_price ?? 'ASK' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Freight Fee
                                    </td>
                                    <td style="text-align: right">{{ $inquiry->inqu_port ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Insurance Fee
                                    </td>
                                    <td style="text-align: right">{{ $inquiry->insurance ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">
                                        Inspection Fee
                                    </td>
                                    <td style="text-align: right">{{ $inquiry->inspection ?? '0' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold" class="total">
                                        Total Amount
                                    </td>
                                    <td style="text-align: right; font-weight: bold" class="total">
                                        {{ $inquiry->total_price ?? 'ASK' }}
                                    </td>
                                </tr>
                            </tbody>
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
                            • This quotation is valid for 30 days from the date of issue.<br>
                            • All prices are in USD and subject to change without notice.<br>
                            • Payment terms: 100% advance payment before shipment.<br>
                            • Shipping and handling charges are additional.<br>
                            • Vehicle condition is as described and photographed.<br>
                            • All sales are final - no returns or exchanges.
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




