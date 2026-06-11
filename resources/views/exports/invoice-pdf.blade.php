<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            background: #fff;
        }
        .invoice-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 25px;
        }
        .company-info {
            display: inline-block;
            width: 50%;
            vertical-align: top;
        }
        .invoice-details {
            display: inline-block;
            width: 48%;
            text-align: right;
            vertical-align: top;
        }
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 5px;
        }
        .client-info {
            margin-bottom: 40px;
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 20px;
        }
        .client-title {
            text-transform: uppercase;
            font-size: 11px;
            font-weight: bold;
            color: #777;
            margin-bottom: 5px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-items th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table-items td {
            border: 1px solid #dee2e6;
            padding: 12px 10px;
            vertical-align: top;
        }
        .total-container {
            text-align: right;
            margin-top: 20px;
        }
        .total-section {
            display: inline-block;
            width: 300px;
            text-align: right;
        }
        .total-row {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #2e7d32;
            border-bottom: 2px solid #333;
            margin-top: 10px;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .badge-unpaid {
            background-color: #ffeef0;
            color: #f84f5a;
        }
        .badge-cancelled {
            background-color: #f1f3f5;
            color: #868e96;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">{{ $companyName }}</div>
                <div style="font-size: 12px; color: #555;">
                    {{ $companyAddress }}<br>
                    Phone: {{ $companyPhone }}<br>
                    Email: {{ $companyEmail }}
                </div>
            </div>
            <div class="invoice-details">
                <h2 style="margin: 0 0 5px 0; color: #333; text-transform: uppercase; font-size: 1.8rem;">Invoice</h2>
                <div style="margin-bottom: 8px;">
                    <span class="badge badge-{{ $invoice->status }}">{{ $invoice->status }}</span>
                </div>
                <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="client-info">
            <div class="client-title">Billed To</div>
            <strong>{{ $invoice->client->name }}</strong><br>
            {{ $invoice->client->email }}<br>
            {{ $invoice->client->address ?: 'No Address Registered' }}
            
            @if($invoice->clientCase)
                <div style="margin-top: 12px; font-size: 12px;">
                    <strong>Matter Reference:</strong> Case #{{ $invoice->clientCase->case_number }} - {{ $invoice->clientCase->title }}
                </div>
            @endif
        </div>

        <table class="table-items">
            <thead>
                <tr>
                    <th>Description of Services Rendered</th>
                    <th style="width: 120px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>
                            @if($invoice->clientCase)
                                Legal/CPA Representation for Case #{{ $invoice->clientCase->case_number }}
                            @else
                                Professional Account Consulting & Retainer Services
                            @endif
                        </strong>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 12px; white-space: pre-line; line-height: 1.4;">
                            {{ $invoice->description ?: 'Retainer fees and professional consulting representation statement.' }}
                        </p>
                    </td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle;">
                        ${{ number_format($invoice->amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total-container">
            <div class="total-section">
                <div class="total-row">
                    <span style="color: #777; float: left;">Subtotal:</span>
                    <span style="font-weight: bold;">${{ number_format($invoice->amount, 2) }}</span>
                    <div style="clear: both;"></div>
                </div>
                <div class="total-row">
                    <span style="color: #777; float: left;">Tax / Surcharges (0%):</span>
                    <span style="font-weight: bold;">$0.00</span>
                    <div style="clear: both;"></div>
                </div>
                <div class="total-row grand-total">
                    <span style="float: left;">Amount Due:</span>
                    <span>${{ number_format($invoice->amount, 2) }}</span>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="footer">
            <p style="font-weight: bold; margin-bottom: 5px;">Thank you for your business!</p>
            <p style="margin: 0;">If you have any questions about this statement, please contact our billing department at {{ $companyEmail }}.</p>
        </div>
    </div>
</body>
</html>
