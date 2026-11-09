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
        <div class="container-fluid" style="overflow-x: hidden;">
            <!-- Mobile & Desktop Admin PWA Install Notification (Correct Header & Sidebar Clearance) -->
            <div id="adminPwaInstallBanner" class="alert d-none shadow border-0 d-flex flex-wrap align-items-center justify-content-between py-2 px-3" style="border-radius: 10px; background: #0f172a !important; border: 1.5px solid #6366f1 !important; color: #ffffff !important; position: relative; z-index: 1025; box-shadow: 0 4px 20px rgba(0,0,0,0.5) !important;">
                <div class="d-flex align-items-center my-1" style="min-width: 0;">
                    <i class="fas fa-shield-alt text-warning mr-2" style="font-size: 1.15rem; color: #f59e0b !important; flex-shrink: 0;"></i>
                    <span class="small font-weight-bold" style="color: #ffffff !important; font-size: 12.5px;">{{ __('Install Admin Command App for instant mobile firm oversight, staff supervision & live client chat') }}</span>
                </div>
                <div class="d-flex align-items-center my-1 flex-shrink-0">
                    <button type="button" id="adminPwaInstallBtn" class="btn btn-sm font-weight-bold shadow-sm mr-2" style="background: #eab308 !important; color: #0f172a !important; border: none !important; font-size: 12px; font-weight: 700; border-radius: 6px; padding: 5px 14px;">
                        <i class="fas fa-download mr-1"></i> {{ __('Install App') }}
                    </button>
                    <button type="button" id="adminPwaDismissBtn" class="btn btn-link btn-sm p-0 ml-1" onclick="dismissAdminPwaBanner(event)" title="Dismiss" style="color: #94a3b8 !important; text-decoration: none;">
                        <i class="fas fa-times" style="font-size: 15px;"></i>
                    </button>
                </div>
            </div>

            <!-- Global PWA Installation Instructions Modal -->
            <div class="modal fade" id="adminPwaGuideModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99999;">
                <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
                    <div class="modal-content" style="background: #0f172a; border: 1.5px solid #6366f1; border-radius: 16px; color: #ffffff; box-shadow: 0 20px 60px rgba(0,0,0,0.85);">
                        <div class="modal-header border-bottom py-3 px-4" style="border-color: #1e293b !important;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3" style="width: 38px; height: 38px; border-radius: 50%; background: rgba(99, 102, 241, 0.2); border: 1px solid #6366f1; display: flex; align-items: center; justify-content: center; color: #a5b4fc;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h6 class="modal-title font-weight-bold mb-0 text-white" style="font-size: 15px;">{{ __('Install Admin Command App') }}</h6>
                                    <small style="color: #94a3b8;">{{ __('Fast, standalone mobile and desktop access') }}</small>
                                </div>
                            </div>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4" style="font-size: 13px; line-height: 1.6; color: #cbd5e1;">
                            <div id="adminPwaIosGuide" class="d-none">
                                <div class="p-3 mb-3 rounded" style="background: #1e293b; border: 1px solid #334155;">
                                    <strong class="text-white d-block mb-2"><i class="fab fa-apple mr-1 text-warning"></i> {{ __('How to install on iPhone & iPad') }}:</strong>
                                    <ol class="pl-3 mb-0" style="color: #cbd5e1;">
                                        <li class="mb-2">{{ __('Tap the') }} <strong class="text-white">{{ __('Share') }}</strong> {{ __('button') }} <i class="fas fa-share-square text-info"></i> {{ __('in Safari toolbar.') }}</li>
                                        <li class="mb-2">{{ __('Scroll down and tap') }} <strong class="text-white">{{ __('Add to Home Screen') }}</strong> <i class="fas fa-plus-square text-warning"></i>.</li>
                                        <li>{{ __('Tap') }} <strong class="text-white">{{ __('Add') }}</strong> {{ __('in the top-right corner to finish.') }}</li>
                                    </ol>
                                </div>
                            </div>
                            <div id="adminPwaGenericGuide">
                                <div class="p-3 mb-3 rounded" style="background: #1e293b; border: 1px solid #334155;">
                                    <strong class="text-white d-block mb-2"><i class="fas fa-desktop mr-1 text-warning"></i> {{ __('Android & Desktop Installation') }}:</strong>
                                    <p class="mb-2" style="color: #cbd5e1;">{{ __('Install the Admin Command App directly on your Android, Windows, or Mac device. Operates in standalone window mode with real-time push alerts, ringing incoming call alerts, and staff oversight.') }}</p>
                                    <button type="button" class="btn btn-warning btn-block font-weight-bold text-dark my-2 py-2" onclick="triggerDirectAdminInstall()" style="border-radius: 8px;">
                                        <i class="fas fa-download mr-1"></i> {{ __('Install Application Now') }}
                                    </button>
                                </div>
                            </div>
                            <div class="text-muted small text-center">
                                <i class="fas fa-bolt text-warning mr-1"></i> {{ __('Enjoy push notifications, ringing alerts & instant offline caching.') }}
                            </div>
                        </div>
                        <div class="modal-footer border-top py-2 px-4 justify-content-between" style="border-color: #1e293b !important;">
                            <button type="button" class="btn btn-sm btn-outline-secondary text-light" data-dismiss="modal">{{ __('Got It') }}</button>
                            <button type="button" class="btn btn-sm font-weight-bold" onclick="markAdminPwaAsInstalled()" style="background: #eab308; color: #0f172a; border-radius: 6px;">
                                <i class="fas fa-check mr-1"></i> {{ __('I\'ve Added to Home Screen') }}
                            </button>
                        </div>
                    </div>
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

    <!-- Admin Portal Global Mobile Responsiveness Overhaul -->
    <style>
    /* PWA Banner Position & Layout Alignment across all screen widths */
    #adminPwaInstallBanner {
        margin-top: 85px !important;
        margin-bottom: 16px !important;
        margin-left: 18.5% !important;
        width: calc(81.5% - 24px) !important;
    }
    #wrapper.toggled #adminPwaInstallBanner {
        margin-left: 80px !important;
        width: calc(100% - 96px) !important;
    }

    /* Mobile-first Toastr Notifications */
    #toast-container {
        z-index: 999999 !important;
    }
    @media (max-width: 768px) {
        #toast-container {
            top: 75px !important;
            right: 12px !important;
            left: 12px !important;
            width: auto !important;
            max-width: calc(100vw - 24px) !important;
        }
        #toast-container > div {
            width: 100% !important;
            margin-bottom: 8px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 18px rgba(0,0,0,0.5) !important;
        }
    }

    @media (max-width: 991.98px) {
        #adminPwaInstallBanner {
            margin-left: 80px !important;
            width: calc(100% - 96px) !important;
        }
        #wrapper.toggled #adminPwaInstallBanner {
            margin-left: 8px !important;
            width: calc(100% - 16px) !important;
        }
        #wrapper-header .navbar {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        #wrapper-header .navbar-nav.mr-5 {
            margin-right: 8px !important;
        }
        #wrapper #wrapper-content {
            padding-top: 75px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }

    @media (max-width: 767.98px) {
        body {
            overflow-x: hidden !important;
        }
        #adminPwaInstallBanner {
            margin-left: 6px !important;
            margin-right: 6px !important;
            width: calc(100% - 12px) !important;
            margin-top: 80px !important;
        }
        .container-fluid {
            padding-left: 6px !important;
            padding-right: 6px !important;
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }
        /* Auto touch horizontal scrolling on all data tables */
        .table-responsive,
        .dataTables_wrapper,
        div[class*="table-responsive"] {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            margin-bottom: 1rem !important;
        }
        table.table,
        table.bapric_table,
        table.dataTable {
            min-width: 580px !important;
        }
        /* DataTables controls responsiveness */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none !important;
            text-align: left !important;
            width: 100% !important;
            margin-bottom: 8px !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            margin-left: 0 !important;
            margin-top: 4px !important;
        }
        .dataTables_wrapper .dataTables_paginate ul.pagination {
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 4px !important;
        }
        /* Prevent cards or boxes from overflowing screen */
        .card {
            margin-bottom: 14px !important;
            width: 100% !important;
        }
        .card .card-body {
            padding: 14px 12px !important;
        }
        /* Breadcrumb wrapping */
        .breadcrumb {
            flex-wrap: wrap !important;
            padding: 8px 12px !important;
            font-size: 12px !important;
        }
        /* Header notification & message dropdown centering */
        #wrapper-header .dropdown-menu {
            position: fixed !important;
            top: 56px !important;
            left: 10px !important;
            right: 10px !important;
            width: auto !important;
            max-width: calc(100vw - 20px) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.85) !important;
            z-index: 1070 !important;
            transform: none !important;
        }
        /* Button groups and action toolbars flex wrap */
        .btn-toolbar,
        .btn-group,
        .action-buttons-wrap,
        .filter-bar-wrap {
            flex-wrap: wrap !important;
            gap: 6px !important;
        }
    }
    </style>

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
                gain.gain.setValueAtTime(0.25, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.35);
            } catch(e) {}
            if ('vibrate' in navigator) {
                try { navigator.vibrate([150, 75, 150]); } catch(e) {}
            }
        }

        function triggerAdminPush(title, body, url) {
            playAdminActivityChime();
            if (typeof showDesktopNotification === 'function') {
                showDesktopNotification(title, body, url);
            }
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
                        triggerAdminPush("💬 Client Message Received", "New client consultation message in Communications Hub", "{{ route('admin.conversation.index') }}");
                        if (typeof toastr !== 'undefined') toastr.info("New Client Chat Message Received", "Communications Hub");
                    }
                    if (c.unread_staff_msgs > (lastKnownActivity.unread_staff_msgs || 0)) {
                        triggerAdminPush("👥 Staff Message Received", "A staff associate sent an encrypted internal message", "{{ route('admin.conversation.index') }}");
                        if (typeof toastr !== 'undefined') toastr.info("New Staff Message Received", "Staff Directory");
                    }
                    if (c.pending_tasks > (lastKnownActivity.pending_tasks || 0)) {
                        triggerAdminPush("📋 Task Submitted for Review", "A completed legal/accounting deliverable requires supervisor approval", "{{ route('admin.staff.tasks.index') }}");
                        if (typeof toastr !== 'undefined') toastr.warning("New Staff Task Submitted for Quality Review", "Legal & CPA Operations");
                    }
                    if (c.pending_payouts > (lastKnownActivity.pending_payouts || 0)) {
                        triggerAdminPush("💰 Staff Payout Requested", "A staff associate submitted a compensation withdrawal request", "{{ route('admin.staff.payouts.index') }}");
                        if (typeof toastr !== 'undefined') toastr.warning("New Staff Payout Claim Requested", "Financial Operations");
                    }
                    if (c.pending_proofs > (lastKnownActivity.pending_proofs || 0)) {
                        triggerAdminPush("💳 Invoice Payment Proof Uploaded", "A client uploaded payment confirmation for invoice review", "{{ route('admin.invoices.index') }}");
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

        const isIos = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        if (isIos) {
            const iosGuide = document.getElementById('adminPwaIosGuide');
            const genericGuide = document.getElementById('adminPwaGenericGuide');
            if (iosGuide) iosGuide.classList.remove('d-none');
            if (genericGuide) genericGuide.classList.add('d-none');
        }

        const isRunningInStandalone = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true
            || (document.referrer && document.referrer.includes('android-app://'));

        if (isRunningInStandalone && adminBanner) {
            adminBanner.remove();
        }

        if ('getInstalledRelatedApps' in navigator) {
            navigator.getInstalledRelatedApps().then(apps => {
                if (apps && apps.length > 0) {
                    localStorage.setItem('admin_pwa_installed', 'true');
                    if (adminBanner) adminBanner.remove();
                } else {
                    // App was uninstalled or not yet installed on this browser
                    if (!isRunningInStandalone) {
                        localStorage.removeItem('admin_pwa_installed');
                        if (adminBanner) {
                            adminBanner.classList.remove('d-none');
                            adminBanner.style.display = 'flex';
                        }
                    }
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
            e.preventDefault();
            deferredAdminPrompt = e;
            // Native prompt confirms app is NOT installed (or was uninstalled)
            localStorage.removeItem('admin_pwa_installed');
            if (adminBanner && !isRunningInStandalone) {
                adminBanner.classList.remove('d-none');
                adminBanner.style.display = 'flex';
            }
        });

        // Always show banner on mobile devices if not running in standalone and not currently installed
        const isMobileDevice = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent);
        if (isMobileDevice && !isRunningInStandalone && !localStorage.getItem('admin_pwa_installed')) {
            if (adminBanner) {
                adminBanner.classList.remove('d-none');
                adminBanner.style.display = 'flex';
            }
        }

        window.triggerDirectAdminInstall = async function() {
            const prompt = window.__appInstallPrompt || deferredAdminPrompt;
            if (prompt) {
                try {
                    prompt.prompt();
                    const { outcome } = await prompt.userChoice;
                    if (outcome === 'accepted') {
                        localStorage.setItem('admin_pwa_installed', 'true');
                        if (adminBanner) adminBanner.remove();
                        if (typeof $ !== 'undefined') $('#adminPwaGuideModal').modal('hide');
                    }
                    window.__appInstallPrompt = null;
                    deferredAdminPrompt = null;
                    return;
                } catch(err) {
                    console.warn('Install prompt execution error:', err);
                }
            }
            if (isIos) {
                if (typeof $ !== 'undefined') $('#adminPwaGuideModal').modal('show');
            } else {
                alert("{{ __('Please click the Install icon in your browser address bar or menu (⋮) to install the Admin app.') }}");
            }
        };

        if (adminInstallBtn) {
            adminInstallBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                const prompt = window.__appInstallPrompt || deferredAdminPrompt;
                if (prompt) {
                    try {
                        prompt.prompt();
                        const { outcome } = await prompt.userChoice;
                        if (outcome === 'accepted') {
                            localStorage.setItem('admin_pwa_installed', 'true');
                            if (adminBanner) adminBanner.remove();
                        }
                        window.__appInstallPrompt = null;
                        deferredAdminPrompt = null;
                        return;
                    } catch(err) {
                        console.warn('Install prompt error:', err);
                    }
                }
                // Fallback: Show clear step-by-step installation instructions modal
                if (typeof $ !== 'undefined') {
                    $('#adminPwaGuideModal').modal('show');
                } else {
                    const modal = document.getElementById('adminPwaGuideModal');
                    if (modal) modal.style.display = 'block';
                }
            });
        }

        const adminDismissBtn = document.getElementById('adminPwaDismissBtn');
        if (adminDismissBtn) {
            adminDismissBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (adminBanner) {
                    adminBanner.style.display = 'none';
                    adminBanner.classList.add('d-none');
                }
                localStorage.setItem('admin_pwa_banner_dismissed', 'true');
            });
        }

        window.dismissAdminPwaBanner = function(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            if (adminBanner) {
                adminBanner.style.display = 'none';
                adminBanner.classList.add('d-none');
            }
            localStorage.setItem('admin_pwa_banner_dismissed', 'true');
        };

        window.markAdminPwaAsInstalled = function() {
            localStorage.setItem('admin_pwa_installed', 'true');
            if (adminBanner) adminBanner.remove();
            if (typeof $ !== 'undefined') {
                $('#adminPwaGuideModal').modal('hide');
            }
        };
    })();
    </script>
</body>

</html>
