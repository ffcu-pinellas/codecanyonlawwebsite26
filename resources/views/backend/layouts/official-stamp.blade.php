@php
    $stampColor = $stampColor ?? '#1e3c72';
    $stampAccent = $stampAccent ?? '#b45309';
    $caseRef = $caseNumber ?? ($caseRef ?? 'YCE-'.date('Y').'-'.rand(1000, 9999));
@endphp
<table class="official-corporate-stamp" style="width: 150px; height: 150px; border: 3px double {{ $stampAccent }}; border-radius: 75px; background: #fffdf5; margin: 0 auto; border-collapse: separate; border-spacing: 0; text-align: center;">
    <tr>
        <td style="vertical-align: middle; padding: 4px; border: 1.5px dashed {{ $stampColor }}; border-radius: 70px; text-align: center;">
            <div style="font-size: 8.5px; font-weight: 800; color: {{ $stampColor }}; text-transform: uppercase; letter-spacing: 1px; line-height: 1.2;">
                YOUR CPA EXPERT
            </div>
            
            <div style="font-size: 7px; font-weight: 700; color: {{ $stampAccent }}; letter-spacing: 1.5px; text-transform: uppercase; margin: 2px 0;">
                ★ OFFICIAL SEAL ★
            </div>

            <div style="font-size: 13px; font-weight: 900; color: {{ $stampAccent }}; margin: 2px 0;">
                ⚖
            </div>

            <div style="font-size: 7.5px; font-weight: 800; color: {{ $stampColor }}; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.1;">
                AUTHENTICATED
            </div>

            <div style="font-size: 6.5px; font-weight: 700; color: #64748b; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px;">
                REG #{{ $caseRef }}
            </div>

            <div style="font-size: 6px; font-weight: 600; color: {{ $stampAccent }}; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px;">
                LEGAL &amp; FINANCIAL PRACTICE
            </div>
        </td>
    </tr>
</table>
