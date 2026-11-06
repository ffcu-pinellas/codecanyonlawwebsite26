@php
    $practicePhone = \App\Models\GeneralSettings::getCompanyPhone();
    $practiceEmail = \App\Models\GeneralSettings::getCompanyEmail();
    $whatsappNum = \App\Models\GeneralSettings::getWhatsappNumber();
    $cleanPhone = preg_replace('/[^0-9]/', '', $practicePhone);
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNum);
@endphp

<style>
    .floating-contact-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: inherit;
    }
    .floating-main-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.45);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        outline: none;
        position: relative;
    }
    .floating-main-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 15px 30px rgba(245, 158, 11, 0.6);
    }
    .floating-main-btn i {
        font-size: 24px;
        transition: transform 0.3s ease;
    }
    .floating-menu-items {
        position: absolute;
        bottom: 75px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(15px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 210px;
    }
    .floating-contact-container.active .floating-menu-items {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .floating-contact-container.active .floating-main-btn i {
        transform: rotate(135deg);
    }
    .floating-action-chip {
        display: flex;
        align-items: center;
        background: #0f172a;
        color: #f8fafc;
        padding: 10px 16px;
        border-radius: 30px;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .floating-action-chip:hover {
        background: #1e293b;
        color: #f59e0b;
        transform: translateX(-4px);
    }
    .floating-action-chip .chip-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 14px;
    }
    .chip-phone { background: rgba(37, 99, 235, 0.2); color: #60a5fa; }
    .chip-whatsapp { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .chip-calendar { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }

    /* Pulse wave effect on button */
    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid #f59e0b;
        animation: pulseEffect 2.2s infinite;
        pointer-events: none;
    }
    @keyframes pulseEffect {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.6); opacity: 0; }
    }
</style>

<div class="floating-contact-container" id="floatingContactWidget">
    <div class="floating-menu-items">
        <a href="tel:{{ $cleanPhone }}" class="floating-action-chip">
            <span class="chip-icon chip-phone"><i class="fas fa-phone-alt"></i></span>
            <span>Call: {{ $practicePhone }}</span>
        </a>
        @if(!empty($cleanWhatsapp))
        <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" class="floating-action-chip">
            <span class="chip-icon chip-whatsapp"><i class="fab fa-whatsapp"></i></span>
            <span>Direct WhatsApp</span>
        </a>
        @endif
        <a href="{{ route('view-contact-page') }}#appointment" class="floating-action-chip">
            <span class="chip-icon chip-calendar"><i class="fas fa-calendar-check"></i></span>
            <span>Book Consultation</span>
        </a>
    </div>
    <button type="button" class="floating-main-btn" id="floatingMainBtn" title="Contact Practice">
        <div class="pulse-ring"></div>
        <i class="fas fa-comments"></i>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('floatingContactWidget');
        var btn = document.getElementById('floatingMainBtn');
        if (btn && container) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                container.classList.toggle('active');
            });
            document.addEventListener('click', function (e) {
                if (!container.contains(e.target)) {
                    container.classList.remove('active');
                }
            });
        }
    });
</script>
