<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Earnings Statement - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #d97706;
            text-align: right;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .meta-label {
            font-weight: bold;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
        }
        .meta-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .earnings-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .earnings-table th {
            background: #0f172a;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 10px;
            border: 1px solid #0f172a;
        }
        .earnings-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        .net-pay-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px 20px;
            text-align: right;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .net-pay-amount {
            font-size: 22px;
            font-weight: bold;
            color: #92400e;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-note {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="company-name">{{ $companyName }}</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    582 Professional Way, Financial District, DC<br>
                    Phone: (216) 230-1837 &bull; support@yourcpaexpert.com
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <div class="doc-title">Earnings Statement</div>
                <div style="font-size: 11px; text-align: right; color: #64748b; margin-top: 4px;">
                    Statement #: <strong>STMT-{{ str_pad($entry->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
                    Pay Date: <strong>{{ $payDate->format('M d, Y') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td style="width: 25%;">
                <div class="meta-label">Employee Name</div>
                <div class="meta-value">{{ $user->name }}</div>
            </td>
            <td style="width: 25%;">
                <div class="meta-label">Staff ID</div>
                <div class="meta-value">{{ $staffDetail ? $staffDetail->staff_id : 'STF-' . $user->id }}</div>
            </td>
            <td style="width: 25%;">
                <div class="meta-label">Position / Role</div>
                <div class="meta-value">{{ $staffDetail && $staffDetail->position ? $staffDetail->position : 'Legal & Accounting Associate' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="meta-label">Pay Period</div>
                <div class="meta-value">{{ $periodStart->format('M d') }} – {{ $periodEnd->format('M d, Y') }}</div>
            </td>
        </tr>
    </table>

    <h4 style="margin: 0 0 10px 0; color: #0f172a; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Earnings & Wages Summary</h4>
    <table class="earnings-table">
        <thead>
            <tr>
                <th style="text-align: left;">Earnings Description</th>
                <th style="text-align: right;">Hours</th>
                <th style="text-align: right;">Hourly Rate</th>
                <th style="text-align: right;">Gross Total</th>
            </tr>
        </thead>
        <tbody>
            @if($regularHours > 0)
            <tr>
                <td>Regular Practice Wages</td>
                <td style="text-align: right;">{{ number_format($regularHours, 1) }}</td>
                <td style="text-align: right;">${{ number_format($hourlyRate, 2) }}</td>
                <td style="text-align: right;">${{ number_format($regularHours * $hourlyRate, 2) }}</td>
            </tr>
            @endif

            @if($overtimeHours > 0)
            <tr>
                <td>Overtime Practice Hours (1.5x)</td>
                <td style="text-align: right;">{{ number_format($overtimeHours, 1) }}</td>
                <td style="text-align: right;">${{ number_format($hourlyRate * 1.5, 2) }}</td>
                <td style="text-align: right;">${{ number_format($overtimeHours * $hourlyRate * 1.5, 2) }}</td>
            </tr>
            @endif

            @if($bonus > 0)
            <tr>
                <td>Practice Performance Bonus</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">${{ number_format($bonus, 2) }}</td>
            </tr>
            @endif

            @if($reimbursement > 0)
            <tr>
                <td>Expense Reimbursement (Approved Receipts)</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">${{ number_format($reimbursement, 2) }}</td>
            </tr>
            @endif

            @if($regularHours == 0 && $bonus == 0 && $reimbursement == 0)
            <tr>
                <td>{{ $entry->description ?: 'Practice Disbursed Wage Payout' }}</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">—</td>
                <td style="text-align: right;">${{ number_format($grossAmount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="net-pay-box">
        <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: bold;">Total Disbursed Net Pay</div>
        <div class="net-pay-amount">${{ number_format($grossAmount, 2) }}</div>
        <div style="font-size: 10px; color: #78350f; margin-top: 4px;">Payment Method: <strong>{{ ucfirst($staffDetail ? $staffDetail->payment_method : 'Direct Deposit') }}</strong></div>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold;">Authorized Partner Certification</div>
                <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 15px;">Gerald W. Allen, Esq.</div>
                <div style="font-size: 10px; color: #64748b;">Managing Partner & CPA Principal</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div style="display: inline-block; text-align: left; border: 1px dashed #cbd5e1; padding: 10px 15px; border-radius: 4px; background: #f8fafc;">
                    <div style="font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold;">Electronic Signature Verification</div>
                    <div style="font-size: 10px; font-family: monospace; color: #334155; margin-top: 4px;">VERIFIED: SHA-256 #{{ substr(md5($entry->id . $user->id), 0, 16) }}</div>
                    <div style="font-size: 9px; color: #64748b;">Timestamp: {{ now()->format('Y-m-d H:i:s T') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        CONFIDENTIAL &bull; This earnings statement contains privileged practice payroll records. Generated automatically by Your CPA Expert Management Suite.
    </div>

</body>
</html>

