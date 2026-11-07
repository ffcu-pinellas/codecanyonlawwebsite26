<!-- In-App WebRTC Voice & Video Call Modal & Engine (Zero 3rd-Party Logins, Native HTML5 WebRTC) -->
<style>
  .webrtc-modal-dialog {
    max-width: 820px;
    margin: 1.75rem auto;
  }
  @media (max-width: 768px) {
    .webrtc-modal-dialog {
      max-width: 95vw;
      margin: 10px auto;
    }
  }
  .webrtc-call-window {
    background: #0b0f17 !important;
    border: 1px solid #28303f !important;
    border-radius: 16px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85);
    overflow: hidden;
    position: relative;
    color: #f1f5f9 !important;
  }
  /* Force High-Contrast Text Overrides to prevent light-mode parent CSS pollution */
  .webrtc-call-window h4, .webrtc-call-window h5, .webrtc-call-window h6,
  .webrtc-call-window strong, .webrtc-call-window b,
  #activeVoiceName, #incomingCallerName, #activeCallParticipantName {
    color: #ffffff !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9) !important;
  }
  #activeVoiceStatusText {
    color: #fecc56 !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    text-shadow: 0 1px 6px rgba(0, 0, 0, 0.8) !important;
  }
  .webrtc-call-window p, .webrtc-call-window small {
    color: #cbd5e1 !important;
  }

  .webrtc-voice-stage {
    min-height: 420px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at center, #1b2434 0%, #0a0d14 75%);
    position: relative;
    padding: 30px 20px;
  }
  .webrtc-video-stage {
    min-height: 480px;
    height: 65vh;
    background: #000;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  /* Acoustic Ripple Soundwave Animation */
  .call-pulse-ring {
    position: relative;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .call-pulse-ring::before, .call-pulse-ring::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid rgba(254, 204, 86, 0.45);
    animation: callRipples 2.4s ease-out infinite;
  }
  .call-pulse-ring::after {
    animation-delay: 1.2s;
  }
  @keyframes callRipples {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(2.1); opacity: 0; }
  }
  .call-avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #fecc56;
    object-fit: cover;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    position: relative;
    z-index: 2;
  }
  .call-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fecc56, #f0a500);
    color: #0b0f17 !important;
    font-size: 42px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fecc56;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    position: relative;
    z-index: 2;
  }
  /* Sound bars equalizer animation */
  .sound-wave-bars {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    height: 24px;
    margin-top: 15px;
    transition: opacity 0.3s ease;
  }
  .sound-wave-bar {
    width: 4px;
    background: #fecc56;
    border-radius: 3px;
    animation: barBounce 1.2s ease-in-out infinite alternate;
  }
  .sound-wave-bar:nth-child(1) { height: 8px; animation-delay: 0.1s; }
  .sound-wave-bar:nth-child(2) { height: 18px; animation-delay: 0.25s; }
  .sound-wave-bar:nth-child(3) { height: 24px; animation-delay: 0.4s; }
  .sound-wave-bar:nth-child(4) { height: 14px; animation-delay: 0.15s; }
  .sound-wave-bar:nth-child(5) { height: 20px; animation-delay: 0.3s; }
  .sound-wave-bar:nth-child(6) { height: 10px; animation-delay: 0.2s; }
  @keyframes barBounce {
    0% { height: 4px; opacity: 0.3; }
    100% { height: 24px; opacity: 1; }
  }
  .webrtc-action-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    border: none;
    transition: all 0.2s ease;
    cursor: pointer;
  }
  .webrtc-action-btn:hover {
    transform: scale(1.08);
  }
  .webrtc-btn-mute {
    background: #28303f;
    color: #f1f5f9;
  }
  .webrtc-btn-mute.active {
    background: #ef4444;
    color: #fff;
  }
  .webrtc-btn-video-toggle {
    background: #28303f;
    color: #f1f5f9;
  }
  .webrtc-btn-video-toggle.active {
    background: #ef4444;
    color: #fff;
  }
  .webrtc-btn-hangup {
    background: #ef4444;
    color: #fff;
    width: 60px;
    height: 60px;
    font-size: 24px;
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
  }
  .webrtc-btn-accept {
    background: #22c55e;
    color: #fff;
    width: 60px;
    height: 60px;
    font-size: 24px;
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
  }
  /* Floating Local Video PiP */
  #webrtcLocalVideo {
    position: absolute;
    bottom: 90px;
    right: 20px;
    width: 170px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #fecc56;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.7);
    z-index: 10;
    background: #11151e;
    transform: scaleX(-1);
  }
  @media (max-width: 576px) {
    #webrtcLocalVideo {
      width: 110px;
      height: 80px;
      bottom: 80px;
      right: 12px;
    }
  }
</style>

<!-- 1. Incoming Call Prompt Modal -->
<div class="modal fade" id="incomingCallModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index: 1099;">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;" role="document">
    <div class="modal-content webrtc-call-window text-white" style="background: #11151e !important; border: 1px solid #28303f; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.85);">
      <div class="modal-body p-4 text-center">
        <!-- Caller Avatar with Pulsing Rings -->
        <div class="d-flex justify-content-center my-3">
          <div class="call-pulse-ring">
            <div id="incomingCallerAvatarBox">
              <div class="call-avatar-placeholder" id="incomingCallerInitial">C</div>
            </div>
          </div>
        </div>

        <h4 class="font-weight-bold text-white mb-1" id="incomingCallerName">Caller</h4>
        <div class="mb-3">
          <span class="badge px-3 py-1 font-weight-bold" id="incomingCallTypeBadge" style="background: rgba(254,204,86,0.18); color: #fecc56; border: 1px solid rgba(254,204,86,0.35); font-size: 12px;">
            <i class="fas fa-phone-alt mr-1"></i> Voice Call
          </span>
        </div>
        <p class="text-muted small mb-4">Incoming live audio/video call. Connect to talk instantly inside your portal workspace.</p>

        <!-- Accept & Decline Action Buttons -->
        <div class="d-flex justify-content-center align-items-center" style="gap: 28px;">
          <div class="text-center">
            <button type="button" class="webrtc-action-btn webrtc-btn-hangup" id="btnDeclineIncomingCall" title="Decline Call">
              <i class="fas fa-phone-slash"></i>
            </button>
            <div class="text-muted small font-weight-bold mt-2">Decline</div>
          </div>
          <div class="text-center">
            <button type="button" class="webrtc-action-btn webrtc-btn-accept" id="btnAcceptIncomingCall" title="Accept Call">
              <i class="fas fa-phone"></i>
            </button>
            <div class="text-muted small font-weight-bold mt-2 text-success">Accept</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 2. Active WebRTC Call Session Modal (Voice & Video) -->
<div class="modal fade" id="activeWebRtcCallModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index: 1098;">
  <div class="modal-dialog webrtc-modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content webrtc-call-window">
      <!-- Call Top Header -->
      <div class="d-flex justify-content-between align-items-center px-4 py-2" style="background: rgba(17, 21, 30, 0.95); border-bottom: 1px solid #28303f;">
        <div class="d-flex align-items-center">
          <span class="badge px-2 py-1 mr-2" id="activeCallModeBadge" style="background: rgba(254,204,86,0.2); color: #fecc56; font-size: 11px;">
            <i class="fas fa-phone-alt mr-1"></i> Live Call
          </span>
          <span class="text-white font-weight-bold small" id="activeCallParticipantName">Connecting...</span>
        </div>
        <div class="d-flex align-items-center">
          <span class="badge badge-dark px-3 py-1 font-weight-bold text-warning" id="activeCallTimer" style="font-size: 12px; letter-spacing: 1px; background: #0f172a; border: 1px solid #334155;">
            00:00
          </span>
        </div>
      </div>

      <!-- Main Stage Container -->
      <div class="modal-body p-0 position-relative" id="webrtcMainStage">
        <!-- Voice Call Stage -->
        <div id="webrtcVoiceView" class="webrtc-voice-stage">
          <div class="call-pulse-ring mb-3">
            <div id="activeVoiceAvatarBox">
              <div class="call-avatar-placeholder" id="activeVoiceInitial">U</div>
            </div>
          </div>
          <h4 class="font-weight-bold text-white mb-1" id="activeVoiceName">Participant</h4>
          <span class="text-warning small font-weight-bold mb-2" id="activeVoiceStatusText">Direct In-App Voice Call</span>
          
          <!-- Equalizer animation -->
          <div class="sound-wave-bars" id="activeVoiceWavebars" style="opacity: 0.4;">
            <div class="sound-wave-bar"></div>
            <div class="sound-wave-bar"></div>
            <div class="sound-wave-bar"></div>
            <div class="sound-wave-bar"></div>
            <div class="sound-wave-bar"></div>
            <div class="sound-wave-bar"></div>
          </div>
        </div>

        <!-- Video Call Stage -->
        <div id="webrtcVideoView" class="webrtc-video-stage d-none">
          <video id="webrtcRemoteVideo" autoplay playsinline class="w-100 h-100" style="object-fit: cover; background: #000;"></video>
          <video id="webrtcLocalVideo" autoplay playsinline muted></video>
        </div>

        <!-- Hidden Audio Element for Sound Output -->
        <audio id="webrtcRemoteAudio" autoplay playsinline></audio>
      </div>

      <!-- Call Control Bar -->
      <div class="p-3 d-flex justify-content-center align-items-center" style="gap: 20px; background: #11151e; border-top: 1px solid #28303f;">
        <button type="button" class="webrtc-action-btn webrtc-btn-mute" id="btnToggleAudio" title="Mute Microphone">
          <i class="fas fa-microphone"></i>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-video-toggle d-none" id="btnToggleVideo" title="Toggle Camera">
          <i class="fas fa-video"></i>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-hangup" id="btnEndActiveCall" title="End Call">
          <i class="fas fa-phone-slash"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- WebRTC Core Engine Script -->
<script>
(function() {
  const currentUserId = {{ Auth::id() ?: 0 }};
  const currentUserName = @json(Auth::user()?->name ?: 'User');
  const currentUserRole = @json(Auth::user()?->getRoleNames()->first() ?: 'client');
  const csrfToken = '{{ csrf_token() }}';

  const rtcConfig = {
    iceServers: [
      { urls: 'stun:stun.l.google.com:19302' },
      { urls: 'stun:stun1.l.google.com:19302' },
      { urls: 'stun:stun2.l.google.com:19302' },
      { urls: 'stun:stun3.l.google.com:19302' },
      { urls: 'stun:stun4.l.google.com:19302' },
      { urls: 'stun:stun.cloudflare.com:3478' },
      { urls: 'stun:stun.services.mozilla.com' },
      { urls: 'stun:stun.ekiga.net' },
      { urls: 'stun:stun.voipbuster.com' },
      { urls: 'stun:openrelay.metered.ca:80' },
      {
        urls: 'turn:openrelay.metered.ca:80',
        username: 'openrelayproject',
        credential: 'openrelayproject'
      },
      {
        urls: 'turn:openrelay.metered.ca:443',
        username: 'openrelayproject',
        credential: 'openrelayproject'
      },
      {
        urls: 'turn:openrelay.metered.ca:443?transport=tcp',
        username: 'openrelayproject',
        credential: 'openrelayproject'
      }
    ],
    iceCandidatePoolSize: 10
  };

  let peerConnection = null;
  let localMediaStream = null;
  let activeCallSession = null;
  let callTimerInterval = null;
  let callSeconds = 0;
  let isCallTimerRunning = false;
  let signalingInterval = null;
  let isCaller = false;
  let pendingLocalCandidates = [];
  let addedCandidateKeys = new Set();
  let audioContext = null;
  let ringtoneInterval = null;
  let titleFlashInterval = null;
  const originalDocTitle = document.title;

  // Universal SDP Sanitizer (RFC 8830 Unified Plan compliance & Chromium parser protection)
  function sanitizeSdp(sdp) {
    if (!sdp || typeof sdp !== 'string') return sdp;

    // 1. Normalize line endings to standard CRLF (\r\n)
    let rawLines = sdp.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n');

    // 2. Filter out problematic legacy duplicate ssrc msid lines that crash modern Chromium parsers:
    // e.g. "a=ssrc:XXXX msid:YYYY ZZZZ Invalid SDP line."
    let cleanLines = [];
    for (let i = 0; i < rawLines.length; i++) {
      const line = rawLines[i].trim();
      if (!line) continue; // skip accidental blank lines

      // If line is legacy Plan B "a=ssrc:... msid:...", filter it out.
      // Modern Unified Plan uses "a=msid:..." at the media section level per RFC 8830.
      if (line.startsWith('a=ssrc:') && line.indexOf(' msid:') !== -1) {
        continue;
      }
      cleanLines.push(line);
    }

    // 3. Strict RFC 4566 line termination with trailing CRLF
    return cleanLines.join('\r\n') + '\r\n';
  }

  // Ask for notification permission unobtrusively on first click
  document.addEventListener('click', function requestNotifOnce() {
    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission().catch(() => {});
    }
    document.removeEventListener('click', requestNotifOnce);
  }, { once: true });

  // 1. Synthesized Telephone Ringer (UK/US dual-tone 440Hz + 480Hz)
  function playTelephoneRing() {
    try {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (!AudioCtx) return;
      if (!audioContext || audioContext.state === 'closed') {
        audioContext = new AudioCtx();
      }
      if (audioContext.state === 'suspended') {
        audioContext.resume();
      }

      function ringBurst() {
        if (!ringtoneInterval) return;
        const now = audioContext.currentTime;
        const osc1 = audioContext.createOscillator();
        const osc2 = audioContext.createOscillator();
        const gain = audioContext.createGain();

        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(440, now);
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(480, now);

        gain.gain.setValueAtTime(0.08, now);
        gain.gain.setValueAtTime(0.08, now + 1.8);
        gain.gain.exponentialRampToValueAtTime(0.0001, now + 1.95);

        osc1.connect(gain);
        osc2.connect(gain);
        gain.connect(audioContext.destination);

        osc1.start(now);
        osc2.start(now);
        osc1.stop(now + 2.0);
        osc2.stop(now + 2.0);
      }

      ringBurst();
      ringtoneInterval = setInterval(ringBurst, 4000);
    } catch (e) {
      console.warn('Ringtone generator error:', e);
    }
  }

  function stopTelephoneRing() {
    if (ringtoneInterval) {
      clearInterval(ringtoneInterval);
      ringtoneInterval = null;
    }
  }

  // 2. Off-tab Title Flashing
  function startTitleFlash(text) {
    stopTitleFlash();
    let state = false;
    titleFlashInterval = setInterval(() => {
      document.title = state ? '📞 ' + text : originalDocTitle;
      state = !state;
    }, 850);
  }

  function stopTitleFlash() {
    if (titleFlashInterval) {
      clearInterval(titleFlashInterval);
      titleFlashInterval = null;
    }
    document.title = originalDocTitle;
  }

  // 3. Desktop OS Notification
  function showDesktopNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
      try {
        const notif = new Notification(title, {
          body: body,
          icon: '/frontend/assets/img/logo/favicon.png',
          requireInteraction: true
        });
        notif.onclick = function() {
          window.focus();
          this.close();
        };
      } catch (e) {}
    }
  }

  // Format timer 00:00
  function formatCallTimer(sec) {
    const m = Math.floor(sec / 60);
    const s = sec % 60;
    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
  }

  function startCallTimer() {
    clearInterval(callTimerInterval);
    callSeconds = 0;
    document.getElementById('activeCallTimer').innerText = '00:00';
    callTimerInterval = setInterval(() => {
      callSeconds++;
      document.getElementById('activeCallTimer').innerText = formatCallTimer(callSeconds);
    }, 1000);
  }

  function stopCallTimer() {
    clearInterval(callTimerInterval);
    callTimerInterval = null;
    isCallTimerRunning = false;
  }

  function markCallConnected() {
    const isVideo = activeCallSession && (activeCallSession.call_type === 'video');
    const statusEl = document.getElementById('activeVoiceStatusText');
    if (statusEl) {
      statusEl.innerText = isVideo ? 'Connected • Encrypted HD Video' : 'Connected • Encrypted HD Voice';
    }
    const waveEl = document.getElementById('activeVoiceWavebars');
    if (waveEl) waveEl.style.opacity = '1';
    if (!isCallTimerRunning) {
      isCallTimerRunning = true;
      startCallTimer();
    }
  }

  // 4. Reliable Media Acquisition with Graceful Fallback
  // If desktop has no camera or webcam is in use, falls back to audio rather than throwing an error!
  async function acquireMediaStream(callType) {
    const isVideo = (callType === 'video');
    let stream = null;

    if (isVideo) {
      try {
        // Attempt 1: Standard HD Video + Audio
        stream = await navigator.mediaDevices.getUserMedia({
          audio: { echoCancellation: true, noiseSuppression: true },
          video: { width: { ideal: 1280 }, height: { ideal: 720 } }
        });
      } catch (err1) {
        console.warn('HD video constraints failed, trying basic video+audio:', err1);
        try {
          // Attempt 2: Basic Video + Audio
          stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: true });
        } catch (err2) {
          console.warn('Camera failed or unavailable (e.g. desktop with no webcam), falling back to audio only:', err2);
          try {
            // Attempt 3: Graceful fallback to pure audio so user isn't rejected
            stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
            const statusEl = document.getElementById('activeVoiceStatusText');
            if (statusEl) statusEl.innerText = 'Connected (Audio only - Camera unavailable)';
          } catch (err3) {
            console.error('All media acquisition failed:', err3);
            throw new Error('Could not access microphone: ' + (err3.message || 'Permission denied'));
          }
        }
      }
    } else {
      // Pure audio mode (Voice Call): video is strictly FALSE. Webcam is NEVER requested!
      try {
        stream = await navigator.mediaDevices.getUserMedia({
          audio: { echoCancellation: true, noiseSuppression: true },
          video: false
        });
      } catch (errAudio1) {
        console.warn('Audio constraints failed, trying basic audio:', errAudio1);
        try {
          stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
        } catch (errAudio2) {
          console.error('Audio capture failed:', errAudio2);
          throw new Error('Could not access microphone: ' + (errAudio2.message || 'Permission denied'));
        }
      }
    }

    localMediaStream = stream;

    const localVid = document.getElementById('webrtcLocalVideo');
    if (isVideo && localVid && stream.getVideoTracks().length > 0) {
      localVid.srcObject = stream;
      localVid.muted = true;
      localVid.play().catch(() => {});
    }
    return stream;
  }

  // 5. STUN Candidate Gatherer Helper (Waits up to maxWaitMs for candidates to populate local SDP)
  function waitForIceGathering(pc, maxWaitMs = 1200) {
    return new Promise(resolve => {
      if (pc.iceGatheringState === 'complete') {
        resolve();
        return;
      }
      const timeout = setTimeout(() => {
        resolve();
      }, maxWaitMs);

      function onStateChange() {
        if (pc.iceGatheringState === 'complete') {
          clearTimeout(timeout);
          pc.removeEventListener('icegatheringstatechange', onStateChange);
          resolve();
        }
      }
      pc.addEventListener('icegatheringstatechange', onStateChange);
    });
  }

  // 6. Setup RTCPeerConnection and wire media tracks
  function setupPeerConnection() {
    if (peerConnection) {
      try { peerConnection.close(); } catch(e) {}
    }
    peerConnection = new RTCPeerConnection(rtcConfig);

    // Bind local media tracks
    if (localMediaStream) {
      localMediaStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localMediaStream);
      });
    }

    // Local ICE Candidate Generation -> Trickle to server
    peerConnection.onicecandidate = function(event) {
      if (event.candidate && event.candidate.candidate) {
        console.log('Gathered ICE candidate:', event.candidate.candidate);
        const candJson = {
          candidate: event.candidate.candidate,
          sdpMid: event.candidate.sdpMid,
          sdpMLineIndex: event.candidate.sdpMLineIndex
        };
        if (activeCallSession && activeCallSession.call_id && (activeCallSession.status === 'ringing' || activeCallSession.status === 'connected')) {
          fetch("{{ route('portal.call.signal') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
              call_id: activeCallSession.call_id,
              action: 'candidate',
              candidate: candJson
            })
          })
          .then(r => r.json())
          .then(res => {
            if (res && res.status === 'ended') {
              hangUpCall(false);
            }
          })
          .catch(() => {});
        } else {
          pendingLocalCandidates.push(candJson);
        }
      }
    };

    // Remote Track Handler: Remote media arrives -> mark call connected!
    peerConnection.ontrack = function(event) {
      console.log('Remote track received:', event.track.kind);
      const remoteAudio = document.getElementById('webrtcRemoteAudio');
      const remoteVideo = document.getElementById('webrtcRemoteVideo');

      if (event.track.kind === 'video' && remoteVideo) {
        if (!remoteVideo.srcObject) {
          remoteVideo.srcObject = new MediaStream();
        }
        remoteVideo.srcObject.addTrack(event.track);
        remoteVideo.play().catch(e => {
          console.warn('Remote video autoplay blocked, muting element to allow rendering:', e);
          remoteVideo.muted = true;
          remoteVideo.play().catch(() => {});
        });
      } else if (event.track.kind === 'audio' && remoteAudio) {
        if (!remoteAudio.srcObject) {
          remoteAudio.srcObject = new MediaStream();
        }
        remoteAudio.srcObject.addTrack(event.track);
        remoteAudio.muted = false;
        remoteAudio.volume = 1.0;
        remoteAudio.play().catch(e => console.warn('Remote audio autoplay warning:', e));
      }
      markCallConnected();
    };

    // Connection State Listeners
    peerConnection.onconnectionstatechange = function() {
      console.log('PeerConnection connectionState:', peerConnection.connectionState);
      if (peerConnection.connectionState === 'connected') {
        markCallConnected();
      } else if (peerConnection.connectionState === 'disconnected' || peerConnection.connectionState === 'failed') {
        const statusEl = document.getElementById('activeVoiceStatusText');
        if (statusEl && !isCallTimerRunning) statusEl.innerText = 'Reconnecting encrypted line...';
      }
    };

    peerConnection.oniceconnectionstatechange = function() {
      console.log('ICE connectionState:', peerConnection.iceConnectionState);
      if (peerConnection.iceConnectionState === 'connected' || peerConnection.iceConnectionState === 'completed') {
        markCallConnected();
      }
    };
  }

  // 7. UI View Switcher
  function updateCallModalUi(callType, otherPartyName, avatarUrl) {
    const isVideo = (callType === 'video');
    const badge = document.getElementById('activeCallModeBadge');
    badge.innerHTML = isVideo ? '<i class="fas fa-video mr-1"></i> Video Call' : '<i class="fas fa-phone-alt mr-1"></i> Voice Call';

    document.getElementById('activeCallParticipantName').innerText = otherPartyName || 'Participant';
    document.getElementById('activeVoiceName').innerText = otherPartyName || 'Participant';

    const initial = (otherPartyName ? otherPartyName.charAt(0).toUpperCase() : 'U');
    document.getElementById('activeVoiceInitial').innerText = initial;

    if (isVideo) {
      document.getElementById('webrtcVoiceView').classList.add('d-none');
      document.getElementById('webrtcVideoView').classList.remove('d-none');
      document.getElementById('btnToggleVideo').classList.remove('d-none');
    } else {
      document.getElementById('webrtcVoiceView').classList.remove('d-none');
      document.getElementById('webrtcVideoView').classList.add('d-none');
      document.getElementById('btnToggleVideo').classList.add('d-none');
    }

    // Reset mute toggles
    document.getElementById('btnToggleAudio').classList.remove('active');
    document.getElementById('btnToggleVideo').classList.remove('active');
  }

  // 8. INITIATE CALL (Caller - Admin / Supervisor)
  window.initiateWebRtcCall = async function(recipientId, recipientName, callType, conversationSlug) {
    if (!recipientId) {
      alert('Recipient ID required.');
      return;
    }

    isCaller = true;
    pendingLocalCandidates = [];
    addedCandidateKeys.clear();

    try {
      await acquireMediaStream(callType);
    } catch (e) {
      alert(e.message || 'Microphone access is required to initiate call.');
      return;
    }

    updateCallModalUi(callType, recipientName, null);
    const ringLabel = (callType === 'video') ? 'Ringing recipient video line...' : 'Ringing recipient voice line...';
    document.getElementById('activeVoiceStatusText').innerText = ringLabel;
    document.getElementById('activeVoiceWavebars').style.opacity = '0.3';
    $('#activeWebRtcCallModal').modal('show');

    // Create RTCPeerConnection and bind local stream
    setupPeerConnection();

    // In Unified Plan, local tracks are already attached; calling createOffer() cleanly
    // without legacy Plan B dictionary flags prevents duplicate a=ssrc: msid: collision lines!
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);

    // Wait up to 600ms for initial STUN/host candidates to be collected
    await waitForIceGathering(peerConnection, 600);

    const cleanLocalOfferSdp = sanitizeSdp(peerConnection.localDescription.sdp);

    const fullOffer = {
      type: peerConnection.localDescription.type || 'offer',
      sdp: cleanLocalOfferSdp
    };

    const initialCandidates = pendingLocalCandidates.slice();
    pendingLocalCandidates = [];

    fetch("{{ route('portal.call.initiate') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        recipient_id: recipientId,
        call_type: callType,
        offer: fullOffer,
        candidates: initialCandidates,
        conversation_slug: conversationSlug || ''
      })
    })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok' && res.call) {
        activeCallSession = res.call;
        startSignalingPoller(res.call_id);
      } else {
        alert(res.error || 'Failed to initiate call.');
        hangUpCall();
      }
    })
    .catch(err => {
      console.error(err);
      alert('Network error initiating call.');
      hangUpCall();
    });
  };

  // 9. SIGNALING POLLER
  function startSignalingPoller(callId) {
    clearInterval(signalingInterval);
    signalingInterval = setInterval(() => {
      if (!activeCallSession) {
        clearInterval(signalingInterval);
        signalingInterval = null;
        return;
      }

      fetch("{{ route('portal.call.signal') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          call_id: callId,
          action: 'poll'
        })
      })
      .then(r => r.json())
      .then(async res => {
        if (!res) return;

        if (res.status === 'ended' || (res.call && inArray(['ended', 'declined', 'cancelled', 'missed'], res.call.status))) {
          clearInterval(signalingInterval);
          signalingInterval = null;
          const status = res.call ? res.call.status : 'ended';
          const label = (status === 'declined') ? 'Call declined by recipient.' : 'Call ended.';
          const statusEl = document.getElementById('activeVoiceStatusText');
          if (statusEl) statusEl.innerText = label;
          setTimeout(() => {
            hangUpCall(false);
          }, 1200);
          return;
        }

        if (!res.call) return;
        const call = res.call;
        activeCallSession = call;

        // Caller receives SDP Answer from Callee
        let answerObj = call.answer;
        if (typeof answerObj === 'string') {
          try { answerObj = JSON.parse(answerObj); } catch(e) {}
        }

        if (isCaller && answerObj && answerObj.sdp && peerConnection && (peerConnection.signalingState === 'have-local-offer')) {
          try {
            const cleanAnswerSdp = sanitizeSdp(answerObj.sdp);
            await peerConnection.setRemoteDescription({
              type: answerObj.type || 'answer',
              sdp: cleanAnswerSdp
            });
            console.log('Caller set remote description from answer successfully!');
            const statusEl = document.getElementById('activeVoiceStatusText');
            if (statusEl && !isCallTimerRunning) {
              statusEl.innerText = 'Connecting encrypted line...';
            }
          } catch(e) {
            console.error('Caller setRemoteDescription error:', e);
          }
        }

        // Trickle ICE: Add any remote ICE candidates from opposite peer
        let remoteCandidates = isCaller ? (call.callee_candidates || []) : (call.caller_candidates || []);
        if (typeof remoteCandidates === 'string') {
          try { remoteCandidates = JSON.parse(remoteCandidates); } catch(e) {}
        }

        if (peerConnection && peerConnection.remoteDescription && Array.isArray(remoteCandidates)) {
          for (const cand of remoteCandidates) {
            if (!cand || !cand.candidate) continue;
            const candKey = cand.candidate;
            if (!addedCandidateKeys.has(candKey)) {
              addedCandidateKeys.add(candKey);
              try {
                await peerConnection.addIceCandidate({
                  candidate: cand.candidate,
                  sdpMid: cand.sdpMid,
                  sdpMLineIndex: cand.sdpMLineIndex
                });
                console.log('Added remote ICE candidate');
              } catch(e) {
                console.warn('addIceCandidate warn:', e);
              }
            }
          }
        }

        // If connection is established on server and ICE is ready, mark connected
        if (call.status === 'connected' && !isCallTimerRunning && peerConnection) {
          if (peerConnection.iceConnectionState === 'connected' || peerConnection.iceConnectionState === 'completed' || peerConnection.connectionState === 'connected') {
            markCallConnected();
          } else if (call.connected_at && (Math.floor(Date.now() / 1000) - call.connected_at) > 3) {
            markCallConnected();
          }
        }
      })
      .catch(() => {});
    }, 800);
  }

  function inArray(arr, val) {
    return Array.isArray(arr) && arr.indexOf(val) !== -1;
  }

  // 10. ACCEPT INCOMING CALL (Callee)
  async function acceptIncomingCall() {
    stopTelephoneRing();
    stopTitleFlash();
    $('#incomingCallModal').modal('hide');

    if (!activeCallSession) return;
    const call = activeCallSession;
    isCaller = false;
    pendingLocalCandidates = [];
    addedCandidateKeys.clear();

    // User gesture audio unlock
    const remoteAudio = document.getElementById('webrtcRemoteAudio');
    if (remoteAudio) {
      remoteAudio.play().catch(() => {});
    }

    try {
      await acquireMediaStream(call.call_type);
    } catch (e) {
      console.error('Acquire media error:', e);
      declineIncomingCall(e.message || 'Microphone permission required');
      return;
    }

    updateCallModalUi(call.call_type, call.caller_name, call.caller_avatar);
    const connLabel = (call.call_type === 'video') ? 'Connecting secure video line...' : 'Connecting secure voice line...';
    document.getElementById('activeVoiceStatusText').innerText = connLabel;
    $('#activeWebRtcCallModal').modal('show');

    setupPeerConnection();

    let offerObj = call.offer;
    if (typeof offerObj === 'string') {
      try { offerObj = JSON.parse(offerObj); } catch(e) {}
    }

    if (!offerObj || !offerObj.sdp) {
      console.error('Call offer is missing SDP content:', offerObj);
      declineIncomingCall('Call offer payload missing SDP');
      return;
    }

    try {
      // 1. Sanitize incoming Offer SDP to eliminate any duplicate ssrc msid lines that crash Chromium/Android parsers
      const cleanOfferSdp = sanitizeSdp(offerObj.sdp);

      // 1. Set Remote Description (the Offer)
      await peerConnection.setRemoteDescription({
        type: offerObj.type || 'offer',
        sdp: cleanOfferSdp
      });

      // 2. Create and Set Local Description (the Answer) FIRST so state becomes stable
      const answer = await peerConnection.createAnswer();
      await peerConnection.setLocalDescription(answer);

      // 3. Immediately queue and apply any existing caller candidates that came with the offer
      let callerCands = call.caller_candidates;
      if (typeof callerCands === 'string') {
        try { callerCands = JSON.parse(callerCands); } catch(e) {}
      }
      if (Array.isArray(callerCands)) {
        for (const cand of callerCands) {
          if (!cand || !cand.candidate) continue;
          if (!addedCandidateKeys.has(cand.candidate)) {
            addedCandidateKeys.add(cand.candidate);
            try {
              await peerConnection.addIceCandidate({
                candidate: cand.candidate,
                sdpMid: cand.sdpMid,
                sdpMLineIndex: cand.sdpMLineIndex
              });
            } catch(e) {}
          }
        }
      }

      // 4. Send Answer to server immediately without artificial delay
      const cleanLocalAnswerSdp = sanitizeSdp(peerConnection.localDescription.sdp);
      const fullAnswer = {
        type: peerConnection.localDescription.type || 'answer',
        sdp: cleanLocalAnswerSdp
      };

      const batchCandidates = pendingLocalCandidates.slice();
      pendingLocalCandidates = [];

      fetch("{{ route('portal.call.signal') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          call_id: call.call_id,
          action: 'answer',
          answer: fullAnswer,
          candidates: batchCandidates
        })
      })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'ok') {
          startSignalingPoller(call.call_id);
        } else if (res.status === 'ended') {
          hangUpCall(false);
        }
      })
      .catch(() => {});
    } catch (errOffer) {
      console.error('Error negotiating offer/answer on callee:', errOffer);
      const errDetail = errOffer ? (errOffer.message || errOffer.name || String(errOffer)) : 'Unknown negotiation error';
      declineIncomingCall('Call setup negotiation error: ' + errDetail);
    }
  }

  // 11. DECLINE INCOMING CALL
  function declineIncomingCall(reason) {
    stopTelephoneRing();
    stopTitleFlash();
    $('#incomingCallModal').modal('hide');

    if (!activeCallSession) return;
    fetch("{{ route('portal.call.respond') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        call_id: activeCallSession.call_id,
        action: 'decline',
        reason: reason || 'User unavailable'
      })
    }).finally(() => {
      activeCallSession = null;
    });
  }

  // 12. HANG UP / END CALL
  function hangUpCall(notifyServer = true) {
    stopTelephoneRing();
    stopTitleFlash();
    stopCallTimer();
    clearInterval(signalingInterval);
    signalingInterval = null;

    if (notifyServer && activeCallSession) {
      fetch("{{ route('portal.call.respond') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          call_id: activeCallSession.call_id,
          action: 'end'
        })
      }).catch(() => {});
    }

    if (localMediaStream) {
      localMediaStream.getTracks().forEach(track => track.stop());
      localMediaStream = null;
    }

    if (peerConnection) {
      peerConnection.close();
      peerConnection = null;
    }

    $('#activeWebRtcCallModal').modal('hide');
    $('#incomingCallModal').modal('hide');
    activeCallSession = null;
    isCaller = false;
    pendingLocalCandidates = [];
    addedCandidateKeys.clear();
    isCallTimerRunning = false;
  }

  // 13. Global Call Check Loop (Runs every 3 seconds across master layouts)
  if (currentUserId > 0) {
    setInterval(() => {
      // Don't poll if already in an active call modal
      if ($('#activeWebRtcCallModal').hasClass('show')) return;

      fetch("{{ route('portal.call.check') }}", {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(res => {
        if (!res || !res.active_call) {
          if ($('#incomingCallModal').hasClass('show')) {
            stopTelephoneRing();
            stopTitleFlash();
            $('#incomingCallModal').modal('hide');
          }
          return;
        }

        const call = res.active_call;
        if (call.status === 'ringing' && call.recipient_id === currentUserId) {
          activeCallSession = call;

          const isVideo = (call.call_type === 'video');
          document.getElementById('incomingCallerName').innerText = call.caller_name || 'Counsel';
          document.getElementById('incomingCallTypeBadge').innerHTML = isVideo
            ? '<i class="fas fa-video mr-1"></i> Incoming Video Call'
            : '<i class="fas fa-phone-alt mr-1"></i> Incoming Voice Call';

          const initial = call.caller_name ? call.caller_name.charAt(0).toUpperCase() : 'C';
          document.getElementById('incomingCallerInitial').innerText = initial;

          if (!$('#incomingCallModal').hasClass('show') && !$('#activeWebRtcCallModal').hasClass('show')) {
            playTelephoneRing();
            startTitleFlash('Incoming ' + (isVideo ? 'Video' : 'Voice') + ' Call (' + (call.caller_name || 'Counsel') + ')...');
            showDesktopNotification('Incoming ' + (isVideo ? 'Video' : 'Voice') + ' Call', (call.caller_name || 'Counsel') + ' is calling you...');
            $('#incomingCallModal').modal('show');
          }
        }
      })
      .catch(() => {});
    }, 3000);
  }

  // UI Event Bindings
  document.addEventListener('DOMContentLoaded', function() {
    const btnAccept = document.getElementById('btnAcceptIncomingCall');
    const btnDecline = document.getElementById('btnDeclineIncomingCall');
    const btnEnd = document.getElementById('btnEndActiveCall');
    const btnAudio = document.getElementById('btnToggleAudio');
    const btnVideo = document.getElementById('btnToggleVideo');

    if (btnAccept) btnAccept.addEventListener('click', acceptIncomingCall);
    if (btnDecline) btnDecline.addEventListener('click', () => declineIncomingCall('Declined by user'));
    if (btnEnd) btnEnd.addEventListener('click', () => hangUpCall(true));

    if (btnAudio) {
      btnAudio.addEventListener('click', function() {
        if (localMediaStream) {
          const track = localMediaStream.getAudioTracks()[0];
          if (track) {
            track.enabled = !track.enabled;
            if (track.enabled) {
              this.classList.remove('active');
              this.innerHTML = '<i class="fas fa-microphone"></i>';
            } else {
              this.classList.add('active');
              this.innerHTML = '<i class="fas fa-microphone-slash"></i>';
            }
          }
        }
      });
    }

    if (btnVideo) {
      btnVideo.addEventListener('click', function() {
        if (localMediaStream) {
          const track = localMediaStream.getVideoTracks()[0];
          if (track) {
            track.enabled = !track.enabled;
            if (track.enabled) {
              this.classList.remove('active');
              this.innerHTML = '<i class="fas fa-video"></i>';
            } else {
              this.classList.add('active');
              this.innerHTML = '<i class="fas fa-video-slash"></i>';
            }
          }
        }
      });
    }
  });
})();
</script>
