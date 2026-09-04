@php
    $stampColor = $stampColor ?? '#1e3c72';
    $stampAccent = $stampAccent ?? '#b45309';
    $caseRef = $caseNumber ?? ($caseRef ?? 'YCE-'.date('Y').'-'.rand(1000, 9999));
@endphp
<div class="official-corporate-stamp" style="display: inline-block; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; user-select: none;">
    <div style="width: 155px; height: 155px; border: 3px double {{ $stampAccent }}; border-radius: 50%; padding: 6px; box-sizing: border-box; background: rgba(254, 243, 199, 0.25); position: relative; margin: 0 auto; box-shadow: 0 0 0 1px rgba(30, 60, 114, 0.2);">
        <div style="width: 100%; height: 100%; border: 1.5px dashed {{ $stampColor }}; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 6px; box-sizing: border-box;">
            <!-- Top Arc Text -->
            <div style="font-size: 8.5px; font-weight: 800; color: {{ $stampColor }}; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">
                YOUR CPA EXPERT
            </div>
            
            <div style="font-size: 7px; font-weight: 700; color: {{ $stampAccent }}; letter-spacing: 1.5px; text-transform: uppercase; margin: 2px 0;">
                ★ OFFICIAL SEAL ★
            </div>

            <!-- Center Emblem -->
            <div style="margin: 2px 0; color: {{ $stampAccent }};">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="{{ $stampAccent }}" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block;">
                    <path d="M12 3v18"></path>
                    <path d="m3 7 9-3 9 3"></path>
                    <path d="M6 10a3 3 0 0 0 6 0"></path>
                    <path d="M12 10a3 3 0 0 0 6 0"></path>
                    <circle cx="12" cy="12" r="2"></circle>
                </svg>
            </div>

            <div style="font-size: 7.5px; font-weight: 800; color: {{ $stampColor }}; text-transform: uppercase; letter-spacing: 0.8px; line-height: 1.1;">
                AUTHENTICATED
            </div>

            <div style="font-size: 6.5px; font-weight: 700; color: #64748b; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px;">
                REG #{{ $caseRef }}
            </div>

            <!-- Bottom Verification -->
            <div style="font-size: 6px; font-weight: 600; color: {{ $stampAccent }}; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px;">
                LEGAL &amp; FINANCIAL PRACTICE
            </div>
        </div>
    </div>
</div>
