@php
    $generalSettings = \App\Models\GeneralSettings::first();
    $companyName = $generalSettings && $generalSettings->site_name ? $generalSettings->site_name : env('APP_NAME', 'Your CPA Expert');

    $contactPage = \App\Models\PageSettings::where('name', 'contact')->first();
    $contactInfo = $contactPage ? $contactPage->sections()->where('name', 'contact_info')->first() : null;
    $emailInfo = $contactPage ? $contactPage->sections()->where('name', 'email')->first() : null;

    // Address
    $companyAddress = env('COMPANY_ADDRESS');
    if (!$companyAddress && $contactInfo) {
        $addressParts = array_filter([$contactInfo->line_one, $contactInfo->line_two]);
        if (!empty($addressParts)) {
            $companyAddress = implode(', ', $addressParts);
        }
    }
    if (!$companyAddress) {
        $companyAddress = '582 Professional Way, Financial District, DC';
    }

    // Phone
    $companyPhone = env('COMPANY_PHONE');
    if (!$companyPhone && $contactInfo && $contactInfo->line_two && preg_match('/[0-9]/', $contactInfo->line_two)) {
        $companyPhone = $contactInfo->line_two;
    }
    if (!$companyPhone) {
        $companyPhone = '(216) 230-1837';
    }

    // Email
    $companyEmail = env('COMPANY_EMAIL');
    if (!$companyEmail && $emailInfo && $emailInfo->line_one) {
        $companyEmail = $emailInfo->line_one;
    }
    if (!$companyEmail) {
        $companyEmail = 'support@yourcpaexpert.com';
    }

    // Logo
    $logoSettings = \App\Models\LogoSettings::first();
    $logoPath = $logoSettings ? $logoSettings->logo : null;
    if (!$logoPath || (!str_starts_with($logoPath, 'http') && !file_exists(public_path($logoPath)))) {
        if (file_exists(public_path('upload/settings/1731322171New_Project-removebg-preview.png'))) {
            $logoPath = '/upload/settings/1731322171New_Project-removebg-preview.png';
        }
    }
    $companyLogo = $logoPath ? asset($logoPath) : null;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            color: #333;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1a252f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
        }
        .form-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #f4f6f8;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            border-left: 4px solid #1e3c72;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            background-color: #fafafa;
            width: 30%;
        }
        .agreement-box {
            font-size: 13px;
            text-align: justify;
            margin-top: 20px;
            line-height: 1.6;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        .signature-table td {
            border: none;
            padding: 15px 10px;
        }
        .line {
            border-bottom: 1px solid #333;
            height: 30px;
        }
        .print-btn-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-print {
            background-color: #1e3c72;
            color: white;
            padding: 10px 25px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .print-btn-container {
                display: none;
            }
            body {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button class="btn-print" onclick="window.print()">Print / Save to PDF</button>
    </div>

    <table class="header-table">
        <tr>
            <td>
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="max-height: 45px; width: auto; max-width: 100%; object-fit: contain; margin-bottom: 8px;"><br>
                @endif
                <div class="company-name">{{ $companyName }}</div>
                <div style="font-size: 12px; color: #777;">Corporate Payroll Department</div>
            </td>
            <td style="text-align: right; font-size: 12px; color: #555; vertical-align: bottom;">
                Office: {{ $companyAddress }}<br>
                Call Us: {{ $companyPhone }}<br>
                Email: {{ $companyEmail }}
            </td>
        </tr>
    </table>

    <div class="form-title">
        Direct Deposit Authorization Form
    </div>

    <div class="section-title">1. Employee Information (Personalized)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Full Name</td>
            <td>{{ $user->name }}</td>
            <td class="info-label">Staff ID</td>
            <td>{{ $staffDetail ? $staffDetail->staff_id : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Email Address</td>
            <td>{{ $user->email }}</td>
            <td class="info-label">Phone Number</td>
            <td>{{ $user->phone ?: 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">2. Banking Account Details (Please Fill)</div>
    <table class="info-table">
        <tr>
            <td class="info-label" style="width: 25%;">Bank Name</td>
            <td colspan="3" style="height: 30px;"></td>
        </tr>
        <tr>
            <td class="info-label" style="width: 25%;">Routing Number (9 Digits)</td>
            <td style="width: 25%; height: 30px;"></td>
            <td class="info-label" style="width: 20%;">Account Number</td>
            <td style="width: 30%; height: 30px;"></td>
        </tr>
        <tr>
            <td class="info-label" style="width: 25%;">Account Type</td>
            <td colspan="3" style="padding: 12px 10px; vertical-align: middle;">
                <span style="margin-right: 35px; display: inline-block; vertical-align: middle;">
                    <span style="border: 1px solid #444; width: 13px; height: 13px; display: inline-block; vertical-align: middle; margin-right: 6px; border-radius: 2px; margin-top: -2px;"></span> Checking
                </span>
                <span style="display: inline-block; vertical-align: middle;">
                    <span style="border: 1px solid #444; width: 13px; height: 13px; display: inline-block; vertical-align: middle; margin-right: 6px; border-radius: 2px; margin-top: -2px;"></span> Savings
                </span>
            </td>
        </tr>
    </table>

    <div class="section-title">3. Authorization & Signature</div>
    <div class="agreement-box">
        I hereby authorize <strong>{{ $companyName }}</strong> to initiate credit entries and, if necessary, debit entries and adjustments for any credit entries in error to my account indicated above. I request that these deposits be made directly to the bank account specified. This authorization is to remain in full force and effect until the Company has received written notification from me of its termination in such time and in such manner as to afford the Company and the Depository a reasonable opportunity to act on it.
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 60%;">
                <div class="line"></div>
                <div style="font-size: 11px; margin-top: 5px;">Employee Signature</div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 30%;">
                <div class="line"></div>
                <div style="font-size: 11px; margin-top: 5px;">Date (DD/MM/YYYY)</div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 40px; font-size: 11px; color: #666; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
        Please attach a voided check or routing confirmation letter from your bank and upload this signed document inside the Payment Preferences section of your Staff Portal.
    </div>
</body>
</html>
