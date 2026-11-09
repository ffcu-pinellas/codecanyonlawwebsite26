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
</body>

</html>
