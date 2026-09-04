<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Georgia", serif;
            color: #1f2937;
            line-height: 1.65;
            margin: 40px;
            padding: 0;
            background-color: #fff;
            -webkit-font-smoothing: antialiased;
        }

        /* Document Title Header */
        .document-title {
            text-align: center;
            margin: 25px 0 35px 0;
        }
        .document-title h2 {
            font-size: 21px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3c72;
            font-weight: 800;
            margin: 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #1e3c72;
            display: inline-block;
        }

        /* Content Styling */
        .content {
            font-size: 14.5px;
            text-align: justify;
            margin-bottom: 40px;
            color: #334155;
        }
        .content p {
            margin-bottom: 16px;
        }
        .content h3, .content h4 {
            color: #1e3c72;
            font-size: 15.5px;
            font-weight: 700;
            margin-top: 24px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Ashish Master Info Table */
        table.info-table, .content table {
            width: 100%;
            margin: 20px 0;
            border: 1px solid #cbd5e1;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 6px;
            overflow: hidden;
            background-color: #ffffff;
        }
        table.info-table th, .content table th {
            background-color: #1e3c72;
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 15px;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        table.info-table td, .content table td {
            padding: 11px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13.5px;
            color: #334155;
        }
        table.info-table tr:nth-child(even), .content table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        table.info-table tr:last-child td, .content table tr:last-child td {
            border-bottom: none;
        }

        /* Ashish Master Highlight Box */
        .highlight-box {
            background-color: #fef3c7;
            border-left: 4px solid #d97706;
            padding: 15px 20px;
            margin: 22px 0;
            border-radius: 4px;
            font-size: 13.5px;
            color: #92400e;
            line-height: 1.6;
        }

        /* Signatures Section */
        .signature-section {
            margin-top: 45px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: none !important;
            background: transparent !important;
        }
        .signature-table td {
            padding: 10px 0;
            border: none !important;
            background: transparent !important;
        }
        .signature-line {
            border-top: 1.5px solid #334155;
            margin-top: 45px;
            padding-top: 8px;
            display: inline-block;
            width: 85%;
        }

        /* Ashish Master Official Footer */
        .official-doc-footer {
            margin-top: 60px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
            font-size: 10px;
            color: #64748b;
            line-height: 1.5;
            page-break-inside: avoid;
        }

        .print-btn-container {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
        }
        .print-btn {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .print-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        @media print {
            .print-btn-container {
                display: none !important;
            }
            body {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            {{ __('Print Certified Document') }}
        </button>
    </div>

    @include('backend.layouts.letterhead')

    <div class="document-title">
        <h2>{{ $title }}</h2>
    </div>

    <div class="content">
        {!! $content !!}
    </div>

    @php
        $attorneyDisplayName = $attorneyName ?? (isset($user) && $user->assignedAttorney ? $user->assignedAttorney->name : 'Gerald W. Allen, Esq.');
        $attorneyDisplayTitle = $attorneyTitle ?? 'Senior Lead Counsel & Practice Director';
    @endphp

    <div class="signature-section">
        <h4 style="color: #1e3c72; text-transform: uppercase; font-size: 14px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
            {{ __('Formal Execution & Certified Verification') }}
        </h4>
        
        <table class="signature-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ __('Authorized Client / Principal Signatory') }}
                    </div>
                    <div class="signature-line">
                        <strong>{{ isset($user) ? $user->name : 'Client Signatory' }}</strong><br>
                        <span style="font-size: 12px; color: #64748b;">Client ID: #{{ isset($user) ? sprintf('%05d', $user->id) : '00001' }}</span><br>
                        <span style="font-size: 12px; color: #64748b;">{{ __('Date:') }} {{ date('F d, Y') }}</span>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    <div style="display: inline-block; text-align: left; width: 90%;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ __('Authorized Legal / CPA Counsel & Corporate Seal') }}
                        </div>
                        
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 15px; margin-top: 10px;">
                            <div style="flex-grow: 1;">
                                <div class="signature-line" style="width: 100%; margin-top: 25px;">
                                    <strong style="color: #1e3c72;">{{ $attorneyDisplayName }}</strong><br>
                                    <span style="font-size: 12px; color: #64748b;">{{ $attorneyDisplayTitle }}</span><br>
                                    <span style="font-size: 12px; color: #64748b;">{{ $companyName }} &bull; Date: {{ date('F d, Y') }}</span>
                                </div>
                            </div>
                            <div>
                                @include('backend.layouts.official-stamp', ['caseNumber' => $caseNumber ?? 'YCE-'.date('Y')])
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Ashish Master Official Footer -->
    <div class="official-doc-footer">
        <table style="width: 100%; border-collapse: collapse; border: none !important; background: transparent !important;">
            <tr>
                <td style="vertical-align: middle; text-align: left; border: none !important; padding: 0;">
                    <strong style="color: #1e3c72; text-transform: uppercase;">&copy; {{ date('Y') }} {{ $companyName }}</strong>. All Rights Reserved.<br>
                    <span>582 Professional Way, Financial District, NY &bull; www.yourcpaexpert.com</span>
                </td>
                <td style="vertical-align: middle; text-align: right; border: none !important; padding: 0;">
                    <span style="display: inline-block; background: #f1f5f9; border: 1px solid #cbd5e1; padding: 3px 8px; border-radius: 4px; font-weight: 700; color: #334155; font-size: 9px; letter-spacing: 0.5px;">
                        PRIVILEGED &amp; CONFIDENTIAL
                    </span>
                </td>
            </tr>
        </table>
        <p style="margin: 8px 0 0 0; font-size: 9px; color: #94a3b8; font-style: italic; text-align: center;">
            This instrument contains confidential and attorney-client / CPA-client privileged materials. Any unauthorized disclosure, distribution, or copying is strictly prohibited.
        </p>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                if (window.location.search.indexOf('auto_print=1') !== -1) {
                    window.print();
                }
            }, 500);
        }
    </script>
</body>
</html>
