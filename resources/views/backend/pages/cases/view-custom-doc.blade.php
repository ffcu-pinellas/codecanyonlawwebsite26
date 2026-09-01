<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->title }}</title>
    <style>
        body { font-family: 'Georgia', serif; background: #fff; color: #222; margin: 0; padding: 0; }
        .doc-wrapper { max-width: 820px; margin: 40px auto; padding: 40px 50px; border: 1px solid #ddd; box-shadow: 0 2px 12px rgba(0,0,0,0.12); }
        .doc-header { text-align: center; border-bottom: 2px solid #b8860b; padding-bottom: 24px; margin-bottom: 28px; }
        .doc-header h1 { font-size: 22px; text-transform: uppercase; letter-spacing: 1.5px; color: #1a1a1a; margin: 0 0 6px; }
        .doc-meta { font-size: 13px; color: #666; }
        .doc-content { font-size: 14px; line-height: 1.85; color: #333; }
        .doc-content p { margin-bottom: 14px; }
        .doc-content ul, .doc-content ol { margin: 12px 0 12px 24px; }
        .doc-footer { margin-top: 50px; border-top: 1px solid #ddd; padding-top: 24px; }
        .sig-block { display: flex; gap: 60px; margin-top: 30px; }
        .sig-line { flex: 1; }
        .sig-line .label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
        .sig-line .line { border-bottom: 1px solid #444; height: 36px; margin-bottom: 6px; }
        .sig-line .name { font-size: 12.5px; color: #444; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .doc-wrapper { box-shadow: none; border: none; margin: 0; padding: 30px; }
        }
    </style>
</head>
<body>
<div class="doc-wrapper">
    <div class="doc-header">
        <h1>{{ $document->title }}</h1>
        <div class="doc-meta">
            <span>Document Type: {{ $document->document_type }}</span> &bull;
            <span>Case: {{ $case->case_number }}</span> &bull;
            <span>Prepared: {{ $document->created_at->format('F d, Y') }}</span>
        </div>
    </div>

    <div class="doc-content">
        {!! $content !!}
    </div>

    @if($document->requires_signature)
    <div class="doc-footer">
        <p style="font-size:13px;color:#666;font-style:italic;">This document requires a client signature to be considered legally executed.</p>
        <div class="sig-block">
            <div class="sig-line">
                <div class="label">Client Signature</div>
                <div class="line">
                    @if($document->is_signed)
                        <span style="color:#1a7a36;font-family:cursive;font-size:20px;line-height:36px;padding:0 8px;">
                            ✓ {{ $case->client->name ?? 'Client' }}
                        </span>
                    @endif
                </div>
                <div class="name">{{ $case->client->name ?? 'Client' }}</div>
            </div>
            <div class="sig-line">
                <div class="label">Authorized Representative</div>
                <div class="line"></div>
                <div class="name">{{ config('app.name', 'Your CPA Expert') }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="no-print" style="text-align:center;padding:20px;">
    <button onclick="window.print()" style="background:#b8860b;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;cursor:pointer;">
        🖨️ Print / Save as PDF
    </button>
    <button onclick="window.history.back()" style="background:#555;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;cursor:pointer;margin-left:10px;">
        ← Back
    </button>
</div>
</body>
</html>
