<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('backend.layouts.head')
{!! $insertHeaderFooter?$insertHeaderFooter->header:'' !!}
<body>
    <!------------------------------------------------------------------------------------------------>
    <!-- WRAPPER ------------------------------------------------------------------------------------->
    <div id="wrapper" class="wrapper-left-fixed wrapper-header-fixed bg-dark">
        <!------------------------------------------------------------------------------------------------>
        @include('backend.layouts.pre-loader')
        @include('components.session-timeout-modal')
        <!------------------------------------------------------------------------------------------------>
        @include('backend.menus.header-menu')
        <!------------------------------------------------------------------------------------------------>
        @include('backend.menus.left-menu')
        <!------------------------------------------------------------------------------------------------>
        <div class="container-fluid">
            <!-- Mobile Admin PWA Install Notification (Sleek Command Pill) -->
            <div id="adminPwaInstallBanner" class="alert d-none my-3 shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between py-2 px-3" style="border-radius: 8px; background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(99, 102, 241, 0.4) !important; color: #fff;">
                <div class="d-flex align-items-center my-1">
                    <i class="fas fa-shield-alt text-warning mr-2" style="font-size: 1.1rem;"></i>
                    <span class="small font-weight-bold">{{ __('Install Admin Command App for instant mobile firm oversight, staff supervision & live client chat') }}</span>
                </div>
                <div class="d-flex align-items-center my-1">
                    <button type="button" id="adminPwaInstallBtn" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 py-1 shadow-sm mr-2" style="font-size: 11.5px; border-radius: 6px;">
                        <i class="fas fa-download mr-1"></i> {{ __('Install App') }}
                    </button>
                    <button type="button" class="btn btn-link text-white btn-sm p-0 ml-1" onclick="dismissAdminPwaBanner()" title="Dismiss">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            @yield('content')
        </div>
        <!------------------------------------------------------------------------------------------------>
        @include('backend.layouts.footer')
    </div>

    <!-- END WRAPPER --------------------------------------------------------------------------------->
    <!------------------------------------------------------------------------------------------------>

    @include('backend.layouts.script')
    @include('components.webrtc-call-modal')
    @yield('page-script')
    @include('backend.layouts.toster-script')
    {!! $insertHeaderFooter?$insertHeaderFooter->footer:'' !!}

    <!-- Admin Global Real-Time Activity & Push Poller -->
    <script>
    (function() {
        let lastKnownActivity = {
            unread_client_msgs: null,
            unread_staff_msgs: null,
            pending_tasks: null,
            pending_payouts: null,
            pending_proofs: null
        };
        let isFirstPoll = true;

        function playAdminActivityChime() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(659.25, now); // E5
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.12); // A5
                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.35);
            } catch(e) {}
        }

        function pollAdminActivity() {
            fetch("{{ route('admin.activity-poll') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data || data.status !== 'success') return;
                const c = data.counts;

                if (!isFirstPoll) {
                    if (c.unread_client_msgs > (lastKnownActivity.unread_client_msgs || 0)) {
                        playAdminActivityChime();
                        if (typeof toastr !== 'undefined') toastr.info("New Client Chat Message Received", "Communications Hub");
                    }
                    if (c.unread_staff_msgs > (lastKnownActivity.unread_staff_msgs || 0)) {
                        playAdminActivityChime();
                        if (typeof toastr !== 'undefined') toastr.info("New Staff Message Received", "Staff Directory");
                    }
                    if (c.pending_tasks > (lastKnownActivity.pending_tasks || 0)) {
                        playAdminActivityChime();
                        if (typeof toastr !== 'undefined') toastr.warning("New Staff Task Submitted for Quality Review", "Legal & CPA Operations");
                    }
                    if (c.pending_payouts > (lastKnownActivity.pending_payouts || 0)) {
                        playAdminActivityChime();
                        if (typeof toastr !== 'undefined') toastr.warning("New Staff Payout Claim Requested", "Financial Operations");
                    }
                    if (c.pending_proofs > (lastKnownActivity.pending_proofs || 0)) {
                        playAdminActivityChime();
                        if (typeof toastr !== 'undefined') toastr.success("New Client Invoice Payment Proof Uploaded", "Billing & Payments");
                    }
                }

                lastKnownActivity = {
                    unread_client_msgs: c.unread_client_msgs,
                    unread_staff_msgs: c.unread_staff_msgs,
                    pending_tasks: c.pending_tasks,
                    pending_payouts: c.pending_payouts,
                    pending_proofs: c.pending_proofs
                };
                isFirstPoll = false;
            })
            .catch(() => {});
        }

        // Initial poll after 1s, then every 5 seconds
        setTimeout(pollAdminActivity, 1000);
        setInterval(pollAdminActivity, 5000);
    })();
    </script>

    <!-- Admin PWA Service Worker Registration & Smart Install Banner -->
    <script>
    (function() {
        let deferredAdminPrompt = null;
        const adminBanner = document.getElementById('adminPwaInstallBanner');
        const adminInstallBtn = document.getElementById('adminPwaInstallBtn');

        const isAdminPwaInstalled = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true
            || (document.referrer && document.referrer.includes('android-app://'))
            || localStorage.getItem('admin_pwa_installed') === 'true';

        if (isAdminPwaInstalled && adminBanner) {
            adminBanner.remove();
        }

        if ('getInstalledRelatedApps' in navigator) {
            navigator.getInstalledRelatedApps().then(apps => {
                if (apps && apps.length > 0) {
                    localStorage.setItem('admin_pwa_installed', 'true');
                    if (adminBanner) adminBanner.remove();
                }
            }).catch(() => {});
        }

        window.addEventListener('appinstalled', () => {
            localStorage.setItem('admin_pwa_installed', 'true');
            if (adminBanner) adminBanner.remove();
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Admin PWA SW registered:', reg.scope))
                    .catch(err => console.warn('Admin PWA SW error:', err));
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            if (isAdminPwaInstalled || localStorage.getItem('admin_pwa_installed') === 'true') {
                return;
            }
            e.preventDefault();
            deferredAdminPrompt = e;
            if (!localStorage.getItem('admin_pwa_banner_dismissed')) {
                if (adminBanner) adminBanner.classList.remove('d-none');
            }
        });

        if (adminInstallBtn) {
            adminInstallBtn.addEventListener('click', async () => {
                if (!deferredAdminPrompt) return;
                deferredAdminPrompt.prompt();
                const { outcome } = await deferredAdminPrompt.userChoice;
                if (outcome === 'accepted') {
                    localStorage.setItem('admin_pwa_installed', 'true');
                    if (adminBanner) adminBanner.remove();
                }
                deferredAdminPrompt = null;
            });
        }

        window.dismissAdminPwaBanner = function() {
            if (adminBanner) adminBanner.classList.add('d-none');
            localStorage.setItem('admin_pwa_banner_dismissed', 'true');
        };
    })();
    </script>
</body>

</html>
