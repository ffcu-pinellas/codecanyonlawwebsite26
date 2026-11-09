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

  /* Top-Pinned Call Dynamic Bar Strict High-Contrast Styles */
  #webrtcTopPinnedCallBar {
    background: #0b0f17 !important;
    color: #ffffff !important;
    border: 2px solid #fecc56 !important;
    max-width: calc(100vw - 20px) !important;
    width: auto !important;
  }
  #webrtcTopPinnedCallBar, #webrtcTopPinnedCallBar * {
    box-sizing: border-box !important;
  }
  #webrtcTopPinnedCallBar #pipCallPeerName {
    color: #ffffff !important;
    font-weight: 700 !important;
    text-shadow: none !important;
  }
  #webrtcTopPinnedCallBar #pipCallTimer {
    background: #000000 !important;
    color: #fecc56 !important;
    border: 1.5px solid #fecc56 !important;
    text-shadow: none !important;
  }
  #webrtcTopPinnedCallBar #pipCallBadge {
    background: rgba(254, 204, 86, 0.2) !important;
    color: #fecc56 !important;
    border: 1px solid rgba(254, 204, 86, 0.5) !important;
  }
  @media (max-width: 575.98px) {
    #webrtcTopPinnedCallBar {
      top: 8px !important;
      padding: 4px 8px !important;
      gap: 4px !important;
    }
    #webrtcTopPinnedCallBar #pipCallPeerName {
      max-width: 76px !important;
      font-size: 11px !important;
    }
    #webrtcTopPinnedCallBar #pipCallTimer {
      font-size: 10.5px !important;
      padding: 2px 4px !important;
    }
    #webrtcTopPinnedCallBar #pipBtnMute,
    #webrtcTopPinnedCallBar #pipBtnHangup {
      width: 28px !important;
      height: 28px !important;
    }
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
  .webrtc-btn-screen-share {
    background: #28303f;
    color: #f1f5f9;
  }
  .webrtc-btn-screen-share.active {
    background: #2563eb !important;
    color: #fff !important;
    box-shadow: 0 0 16px rgba(37, 99, 235, 0.65);
  }
  .webrtc-btn-call-chat {
    background: #28303f;
    color: #f1f5f9;
  }
  .webrtc-btn-call-chat.active {
    background: #fecc56 !important;
    color: #000 !important;
  }
  .in-call-msg-mine {
    background: linear-gradient(135deg, rgba(254, 204, 86, 0.22), rgba(240, 165, 0, 0.22));
    border: 1px solid rgba(254, 204, 86, 0.4);
    color: #ffffff;
    align-self: flex-end;
    border-radius: 12px 12px 2px 12px;
    padding: 7px 11px;
    max-width: 86%;
    word-break: break-word;
  }
  .in-call-msg-theirs {
    background: #1e293b;
    border: 1px solid #334155;
    color: #f1f5f9;
    align-self: flex-start;
    border-radius: 12px 12px 12px 2px;
    padding: 7px 11px;
    max-width: 86%;
    word-break: break-word;
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

  /* WhatsApp / Zoom Style Picture-in-Picture Floating Window */
  #activeWebRtcCallModal.webrtc-pip-mode {
    position: fixed !important;
    top: auto !important;
    left: auto !important;
    bottom: 24px !important;
    right: 24px !important;
    width: 320px !important;
    max-width: 90vw !important;
    height: auto !important;
    z-index: 999999 !important;
    pointer-events: none !important;
    overflow: visible !important;
    background: transparent !important;
    padding: 0 !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .modal-dialog {
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    transform: none !important;
    pointer-events: auto !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .modal-content {
    border-radius: 16px !important;
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.95), 0 0 0 2px #fecc56 !important;
    background: #0f172a !important;
    overflow: hidden !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .webrtc-voice-stage {
    min-height: 110px !important;
    padding: 12px !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .call-pulse-ring,
  #activeWebRtcCallModal.webrtc-pip-mode .call-avatar-placeholder {
    width: 46px !important;
    height: 46px !important;
    font-size: 18px !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .sound-wave-bars {
    display: none !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode #webrtcVideoView {
    height: 170px !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode #webrtcInCallChatDrawer {
    display: none !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .webrtc-control-bar {
    padding: 8px 12px !important;
    gap: 10px !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode .webrtc-action-btn {
    width: 36px !important;
    height: 36px !important;
    font-size: 13px !important;
  }
  #activeWebRtcCallModal.webrtc-pip-mode #btnToggleScreenShare,
  #activeWebRtcCallModal.webrtc-pip-mode #btnToggleCallChat {
    display: none !important;
  }
  @media (max-width: 576px) {
    #activeWebRtcCallModal.webrtc-pip-mode {
      bottom: 14px !important;
      right: 14px !important;
      left: 14px !important;
      width: calc(100vw - 28px) !important;
      max-width: 100vw !important;
    }
  }
</style>

<!-- Global Floating Message Toast Notification Banner -->
<div id="globalMessageToast" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999999; display: none; max-width: 360px; width: calc(100vw - 40px); background: #161a23; border: 1.5px solid #fecc56; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.85); padding: 14px 16px; transition: all 0.3s ease;">
  <div class="d-flex align-items-start" style="gap: 12px;">
    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(254,204,86,0.15); color: #fecc56; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; border: 1.5px solid #fecc56;">
      💬
    </div>
    <div class="flex-grow-1" style="min-width: 0;">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <strong class="text-white text-truncate" id="globalToastSender" style="font-size: 13.5px;">Sender</strong>
        <small class="text-muted" id="globalToastTime">Now</small>
      </div>
      <p class="text-muted small mb-2 text-truncate" id="globalToastText" style="font-size: 12px; color: #cbd5e1 !important;">Message snippet...</p>
      <div class="d-flex align-items-center" style="gap: 8px;">
        <a href="#" id="globalToastReplyBtn" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 py-1" style="font-size: 11.5px; border-radius: 6px;">
          <i class="fas fa-reply mr-1"></i> View & Reply
        </a>
        <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="document.getElementById('globalMessageToast').style.display='none';" style="font-size: 11px;">
          Dismiss
        </button>
      </div>
    </div>
  </div>
</div>

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

<!-- Dedicated Top-Level WebRTC Audio Element (Never affected by modal display toggles or minimize mode) -->
<audio id="webrtcRemoteAudio" autoplay playsinline style="position: fixed; width: 1px; height: 1px; opacity: 0; pointer-events: none; z-index: -1;"></audio>
<audio id="webrtcRingtoneAudio" loop playsinline preload="auto" style="position: fixed; width: 1px; height: 1px; opacity: 0; pointer-events: none; z-index: -1;"></audio>

<!-- 1B. Top-Pinned Floating Call Dynamic Bar (WhatsApp & FaceTime Style) -->
<div id="webrtcTopPinnedCallBar" style="position: fixed; top: 12px; left: 50%; transform: translateX(-50%); z-index: 2147483647 !important; display: none; background: #0b1120 !important; border: 2px solid #fecc56 !important; border-radius: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.95), 0 0 25px rgba(254,204,86,0.4); padding: 6px 12px; align-items: center; justify-content: space-between; gap: 8px; width: 94%; max-width: 440px; box-sizing: border-box; backdrop-filter: blur(16px);">
  <div class="d-flex align-items-center" style="gap: 6px; cursor: pointer; min-width: 0;" onclick="toggleCallMinimize(false)" title="Click to Return to Full Call Window">
    <span style="width: 9px; height: 9px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 10px #22c55e; display: inline-block; flex-shrink: 0;"></span>
    <span class="badge px-2 py-1 font-weight-bold d-none d-sm-inline-flex" id="pipCallBadge" style="background: rgba(254,204,86,0.25) !important; color: #fef08a !important; border: 1px solid rgba(254,204,86,0.6) !important; font-size: 10.5px !important; flex-shrink: 0;">
      <i class="fas fa-phone-alt mr-1 text-warning"></i> Live
    </span>
    <span class="text-truncate font-weight-bold" id="pipCallPeerName" style="color: #ffffff !important; max-width: 90px; font-size: 12px !important; display: inline-block; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Caller</span>
    <span class="badge px-2 py-1 font-weight-bold" id="pipCallTimer" style="background: #000000 !important; color: #fecc56 !important; border: 1.5px solid #fecc56 !important; font-size: 11px !important; font-weight: 800 !important; font-family: monospace; letter-spacing: 0.5px; flex-shrink: 0;">00:00</span>
  </div>
  <div class="d-flex align-items-center flex-shrink-0" style="gap: 5px;">
    <button type="button" class="btn btn-sm text-white rounded-circle p-0" id="pipBtnMute" title="Mute/Unmute" style="width: 30px; height: 30px; background: #1e293b !important; color: #ffffff !important; border: 1px solid #64748b !important; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
      <i class="fas fa-microphone" style="font-size: 11px; color: #ffffff !important;"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger rounded-circle p-0" id="pipBtnHangup" title="End Call" style="width: 30px; height: 30px; background: #dc2626 !important; color: #ffffff !important; border: 1.5px solid #ffffff !important; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(239,68,68,0.6); flex-shrink: 0;">
      <i class="fas fa-phone-slash" style="font-size: 11px; color: #ffffff !important;"></i>
    </button>
    <button type="button" class="btn btn-sm font-weight-bold text-dark px-2 py-1" onclick="toggleCallMinimize(false)" title="Expand Call Window" style="background: linear-gradient(135deg, #fecc56, #f0a500) !important; color: #000000 !important; border: 1.5px solid #ffffff !important; border-radius: 20px; font-size: 11px; display: inline-flex; align-items: center; gap: 3px; box-shadow: 0 2px 8px rgba(254,204,86,0.4); flex-shrink: 0;">
      <i class="fas fa-expand-alt" style="font-size: 10px; color: #000000 !important;"></i>
      <span class="d-none d-sm-inline" style="font-size: 10.5px; font-weight: 700; color: #000000 !important;"> Expand</span>
    </button>
  </div>
</div>

<!-- 2. Active WebRTC Call Session Modal (Voice & Video) -->
<div class="modal fade" id="activeWebRtcCallModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index: 1098;">
  <div class="modal-dialog webrtc-modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content webrtc-call-window">
      <!-- Call Top Header -->
      <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background: rgba(17, 21, 30, 0.98); border-bottom: 1px solid #28303f;">
        <div class="d-flex align-items-center" style="min-width: 0;">
          <span class="badge px-2 py-1 mr-2" id="activeCallModeBadge" style="background: rgba(254,204,86,0.2); color: #fecc56; font-size: 11px; flex-shrink: 0;">
            <i class="fas fa-phone-alt mr-1"></i> Live Call
          </span>
          <span class="text-white font-weight-bold small text-truncate" id="activeCallParticipantName" style="max-width: 140px;">Connecting...</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
          <span class="badge px-3 py-1 font-weight-bold" id="activeCallTimer" style="font-size: 14px !important; font-weight: 900 !important; font-family: monospace, sans-serif !important; letter-spacing: 1.5px !important; background: #000000 !important; color: #fecc56 !important; border: 2px solid #fecc56 !important; border-radius: 8px !important; padding: 4px 10px !important; box-shadow: 0 0 10px rgba(254,204,86,0.3) !important;">
            00:00
          </span>
          <button type="button" class="btn btn-sm font-weight-bold text-dark" id="btnMinimizeCall" onclick="toggleCallMinimize(true)" title="Minimize Call (Pin to Top Bar)" style="background: linear-gradient(135deg, #fecc56, #f0a500) !important; color: #000000 !important; border: 1.5px solid #ffffff !important; border-radius: 8px; padding: 4px 10px; font-size: 11.5px; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 10px rgba(254, 204, 86, 0.4); cursor: pointer;">
            <i class="fas fa-compress-alt" id="iconMinimizeCall" style="font-size: 12px; color: #000000 !important;"></i>
            <span style="font-size: 11px; font-weight: 800; color: #000000 !important;">Minimize</span>
          </button>
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
          <h4 class="font-weight-bold mb-1" id="activeVoiceName" style="color: #ffffff !important; font-size: 22px !important; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9) !important;">Participant</h4>
          <span class="small font-weight-bold mb-2" id="activeVoiceStatusText" style="color: #fecc56 !important; font-size: 14px !important; text-shadow: 0 1px 6px rgba(0, 0, 0, 0.8) !important;">Direct In-App Voice Call</span>
          
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

        <!-- In-Call Mini Chat Drawer (Crisp/Zoom style) -->
        <div id="webrtcInCallChatDrawer" class="d-none position-absolute" style="top: 0; right: 0; bottom: 0; width: 330px; max-width: 100%; background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(12px); border-left: 1px solid #334155; z-index: 105; flex-direction: column;">
          <!-- Drawer Header -->
          <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="border-color: #334155 !important; background: rgba(17, 21, 30, 0.9);">
            <span class="text-white small font-weight-bold"><i class="fas fa-comments text-warning mr-1"></i> In-Call Messages</span>
            <button type="button" class="btn btn-sm text-muted p-0" id="btnCloseInCallChat" title="Close Drawer" style="font-size: 16px;">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <!-- Message Feed -->
          <div class="flex-grow-1 p-3 overflow-auto" id="inCallChatMessagesList" style="font-size: 12px; display: flex; flex-direction: column; gap: 8px;">
            <div class="text-muted small text-center my-1" style="font-size: 11px; opacity: 0.8;">
              🔒 Encrypted peer-to-peer live in-call channel.
            </div>
          </div>

          <!-- Quick Emoji Shortcuts -->
          <div class="px-2 py-1 d-flex overflow-auto border-top" style="border-color: #1e293b !important; gap: 8px; background: rgba(0,0,0,0.25);">
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">👍</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">📁</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">⚖️</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">✅</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">💼</button>
            <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none in-call-emoji-btn" style="font-size: 14px;">🤝</button>
          </div>

          <!-- Message Input Bar -->
          <div class="p-2 border-top" style="border-color: #334155 !important; background: #0b0f19;">
            <form id="inCallChatForm" class="d-flex" style="gap: 6px;">
              <input type="text" id="inCallChatInput" class="form-control form-control-sm text-white" placeholder="Type note, link, number..." style="background: #1e293b; border: 1px solid #475569; font-size: 12px;" autocomplete="off">
              <button type="submit" class="btn btn-warning btn-sm px-3 font-weight-bold" style="background: #fecc56; border: none; color: #000;">
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Call Control Bar -->
      <div class="p-3 d-flex justify-content-center align-items-center" style="gap: 16px; background: #11151e; border-top: 1px solid #28303f;">
        <button type="button" class="webrtc-action-btn webrtc-btn-mute" id="btnToggleAudio" title="Mute Microphone">
          <i class="fas fa-microphone"></i>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-video-toggle d-none" id="btnToggleVideo" title="Toggle Camera">
          <i class="fas fa-video"></i>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-screen-share" id="btnToggleScreenShare" title="Share Screen">
          <i class="fas fa-desktop"></i>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-call-chat position-relative" id="btnToggleCallChat" title="In-Call Chat">
          <i class="fas fa-comments"></i>
          <span class="badge badge-danger position-absolute d-none" id="inCallChatUnreadBadge" style="top: -4px; right: -4px; font-size: 9px; border-radius: 50%;">0</span>
        </button>

        <button type="button" class="webrtc-action-btn webrtc-btn-hangup" id="btnEndActiveCall" title="End Call">
          <i class="fas fa-phone-slash"></i>
        </button>
      </div>
    </div>
  </div>
</div>

@php
  $webrtcActiveUser = Auth::guard('admin')->user() ?: Auth::user();
@endphp
<!-- WebRTC Core Engine Script -->
<script>
(function() {
  const currentUserId = {{ $webrtcActiveUser ? $webrtcActiveUser->id : 0 }};
  const currentUserName = @json($webrtcActiveUser?->name ?: 'User');
  const currentUserRole = @json($webrtcActiveUser?->getRoleNames()->first() ?: 'client');
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

  var peerConnection = null;
  var localMediaStream = null;
  var activeCallSession = null;
  var callTimerInterval = null;
  var callSeconds = 0;
  var isCallTimerRunning = false;
  var signalingInterval = null;
  var isCaller = false;
  var pendingLocalCandidates = [];
  var addedCandidateKeys = new Set();
  var audioContext = null;
  var ringtoneInterval = null;
  var titleFlashInterval = null;
  var originalDocTitle = document.title;
  var dataChannel = null;
  var isScreenSharing = false;
  var screenStream = null;
  var originalCameraTrack = null;
  var consecutiveEmptyCallPolls = 0;

  // Universal SDP Sanitizer (RFC 8830 Unified Plan compliance & line ending normalization)
  function sanitizeSdp(sdp) {
    if (!sdp || typeof sdp !== 'string') return sdp;

    // 1. Normalize line endings to standard CRLF (\r\n)
    let rawLines = sdp.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n');

    let cleanLines = [];
    for (let i = 0; i < rawLines.length; i++) {
      let line = rawLines[i].trim();
      if (!line) continue; // skip accidental blank lines

      // Clean any accidental trailing parser error tokens on ssrc msid lines
      // Format: a=ssrc:<id> msid:<stream-id> [track-id]
      if (line.startsWith('a=ssrc:') && line.indexOf(' msid:') !== -1) {
        const parts = line.split(/\s+/);
        if (parts.length > 3) {
          line = parts.slice(0, 3).join(' ');
        }
      }
      cleanLines.push(line);
    }

    // 2. Strict RFC 4566 line termination with trailing CRLF
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
  let isTelephoneRinging = false;
  function playTelephoneRing() {
    try {
      isTelephoneRinging = true;

      // Play audio element for persistent background playback
      const rAudio = document.getElementById('webrtcRingtoneAudio');
      if (rAudio) {
        try {
          rAudio.volume = 1.0;
          rAudio.play().catch(() => {});
        } catch(e) {}
      }

      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (AudioCtx) {
        if (!audioContext || audioContext.state === 'closed') {
          audioContext = new AudioCtx();
        }
        if (audioContext.state === 'suspended') {
          audioContext.resume().catch(() => {});
        }
      }

      function ringBurst() {
        if (!isTelephoneRinging) return;
        try {
          if (audioContext && audioContext.state === 'suspended') {
            audioContext.resume().catch(() => {});
          }
          if (audioContext) {
            const now = audioContext.currentTime;
            const osc1 = audioContext.createOscillator();
            const osc2 = audioContext.createOscillator();
            const gain = audioContext.createGain();

            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(440, now);
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(480, now);

            gain.gain.setValueAtTime(0.9, now);
            gain.gain.setValueAtTime(0.9, now + 1.8);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 1.95);

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(audioContext.destination);

            osc1.start(now);
            osc2.start(now);
            osc1.stop(now + 2.0);
            osc2.stop(now + 2.0);
          }
        } catch(e) {}

        if ('vibrate' in navigator) {
          try { navigator.vibrate([600, 250, 600, 250, 1000]); } catch(e) {}
        }
      }

      ringBurst();
      clearInterval(ringtoneInterval);
      ringtoneInterval = setInterval(ringBurst, 3200);
    } catch (e) {
      console.warn('Ringtone generator error:', e);
    }
  }

  function stopTelephoneRing() {
    isTelephoneRinging = false;
    const rAudio = document.getElementById('webrtcRingtoneAudio');
    if (rAudio) {
      try {
        rAudio.pause();
        rAudio.currentTime = 0;
      } catch(e) {}
    }
    if (ringtoneInterval) {
      clearInterval(ringtoneInterval);
      ringtoneInterval = null;
    }
    if ('vibrate' in navigator) {
      try { navigator.vibrate(0); } catch(e) {}
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

  // 3. Desktop & Android Mobile Native Notification (Service Worker + Fallback)
  function showDesktopNotification(title, body, clickUrl) {
    if (!('Notification' in window)) return;
    const targetUrl = clickUrl || window.location.href;
    const iconUrl = '{{ asset("assets/images/logo/logo.png") }}';
    const isCall = title.toLowerCase().includes('call');

    if (Notification.permission === 'granted') {
      const notifOptions = {
        body: body,
        icon: iconUrl,
        badge: iconUrl,
        vibrate: isCall ? [500, 250, 500, 250, 1000, 500, 500, 250, 500, 250, 1000] : [200, 100, 200],
        tag: isCall ? 'incoming-call' : ('chat-msg-' + Date.now()),
        renotify: true,
        requireInteraction: isCall,
        data: { url: targetUrl },
        actions: isCall ? [
          { action: 'answer', title: 'Answer Call' },
          { action: 'decline', title: 'Decline' }
        ] : [
          { action: 'open', title: 'Open Workspace' }
        ]
      };

      if ('vibrate' in navigator) {
        try {
          if (isCall) {
            navigator.vibrate([500, 250, 500, 250, 1000, 500, 500, 250, 500, 250, 1000]);
          } else {
            navigator.vibrate([180, 80, 180]);
          }
        } catch(e) {}
      }

      // Check service worker registration first for mobile Android/PWA
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistration().then(function(reg) {
          if (reg && reg.showNotification) {
            reg.showNotification(title, notifOptions);
          } else {
            fallbackShowNotification(title, notifOptions, targetUrl);
          }
        }).catch(function() {
          fallbackShowNotification(title, notifOptions, targetUrl);
        });
      } else {
        fallbackShowNotification(title, notifOptions, targetUrl);
      }
    } else if (Notification.permission === 'default') {
      Notification.requestPermission().catch(() => {});
    }
  }

  function fallbackShowNotification(title, options, targetUrl) {
    try {
      const notif = new Notification(title, options);
      notif.onclick = function() {
        window.focus();
        if (targetUrl) window.location.href = targetUrl;
        this.close();
      };
    } catch(e) {}
  }

  let callStartTimeMs = null;

  // Format timer 00:00 or 00:00:00 for long calls
  function formatCallTimer(sec) {
    sec = Math.max(0, parseInt(sec) || 0);
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    const mm = (m < 10 ? '0' + m : m);
    const ss = (s < 10 ? '0' + s : s);
    if (h > 0) {
      const hh = (h < 10 ? '0' + h : h);
      return hh + ':' + mm + ':' + ss;
    }
    return mm + ':' + ss;
  }

  function startCallTimer(initialSec = 0) {
    if (callTimerInterval) {
      clearInterval(callTimerInterval);
      callTimerInterval = null;
    }
    isCallTimerRunning = true;
    callSeconds = Math.max(0, parseInt(initialSec) || 0);
    callStartTimeMs = Date.now() - (callSeconds * 1000);

    function tick() {
      if (!isCallTimerRunning) return;
      if (callStartTimeMs) {
        callSeconds = Math.max(0, Math.floor((Date.now() - callStartTimeMs) / 1000));
      } else {
        callSeconds++;
      }
      const formatted = formatCallTimer(callSeconds);
      const el = document.getElementById('activeCallTimer');
      if (el) {
        el.innerText = formatted;
      }
      const pipTimer = document.getElementById('pipCallTimer');
      if (pipTimer) {
        pipTimer.innerText = formatted;
      }
    }

    tick();
    callTimerInterval = setInterval(tick, 1000);
  }

  function stopCallTimer() {
    if (callTimerInterval) {
      clearInterval(callTimerInterval);
      callTimerInterval = null;
    }
    isCallTimerRunning = false;
    callStartTimeMs = null;
  }

  function markCallConnected(connectedAtTimestamp = null) {
    const isVideo = activeCallSession && (activeCallSession.call_type === 'video');
    const statusEl = document.getElementById('activeVoiceStatusText');
    if (statusEl) {
      statusEl.innerText = isVideo ? 'Connected • Encrypted HD Video' : 'Connected • Encrypted HD Voice';
    }
    const waveEl = document.getElementById('activeVoiceWavebars');
    if (waveEl) waveEl.style.opacity = '1';

    let initialElapsed = 0;
    if (connectedAtTimestamp && parseInt(connectedAtTimestamp) > 0) {
      const nowSec = Math.floor(Date.now() / 1000);
      const diff = nowSec - parseInt(connectedAtTimestamp);
      if (diff > 0 && diff < 86400) {
        initialElapsed = diff;
      }
    }

    if (!isCallTimerRunning) {
      startCallTimer(initialElapsed);
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

    // Pre-negotiate video transceiver ONLY for video calls or when video track present
    const hasVideoTrack = localMediaStream && localMediaStream.getVideoTracks().length > 0;
    if (!hasVideoTrack && activeCallSession && activeCallSession.call_type === 'video') {
      try {
        peerConnection.addTransceiver('video', { direction: 'sendrecv' });
      } catch(e) {
        console.warn('Could not pre-add video transceiver:', e);
      }
    }

    // Initialize In-Call DataChannel (Peer-to-peer real-time messaging)
    initDataChannel();

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

      if (event.track.kind === 'video' && remoteVideo && activeCallSession && activeCallSession.call_type === 'video') {
        event.track.enabled = true;
        // Strictly isolated MediaStream for video ONLY: Never attach audio track to remoteVideo so muting video NEVER silences audio!
        remoteVideo.srcObject = new MediaStream([event.track]);
        remoteVideo.muted = true;
        remoteVideo.play().catch(e => console.warn('Remote video play warning:', e));

        // When a remote video or screen track arrives, show the video stage!
        document.getElementById('webrtcVoiceView')?.classList.add('d-none');
        document.getElementById('webrtcVideoView')?.classList.remove('d-none');
        document.getElementById('btnToggleVideo')?.classList.remove('d-none');

        event.track.onended = function() {
          if (activeCallSession && activeCallSession.call_type === 'audio' && !isScreenSharing) {
            document.getElementById('webrtcVoiceView')?.classList.remove('d-none');
            document.getElementById('webrtcVideoView')?.classList.add('d-none');
            document.getElementById('btnToggleVideo')?.classList.add('d-none');
          }
        };
      } else if (event.track.kind === 'audio' && remoteAudio) {
        event.track.enabled = true;
        // Connect stream or single track
        const incomingAudioStream = (event.streams && event.streams[0]) ? event.streams[0] : new MediaStream([event.track]);
        remoteAudio.srcObject = incomingAudioStream;
        remoteAudio.muted = false;
        remoteAudio.volume = 1.0;
        const playPromise = remoteAudio.play();
        if (playPromise !== undefined) {
          playPromise.then(() => {
            console.log('WebRTC remote audio stream outputting sound successfully!');
          }).catch(err => {
            console.warn('Remote audio autoplay blocked, unlocking on next touch/click:', err);
            const unlockAudio = () => {
              remoteAudio.muted = false;
              remoteAudio.volume = 1.0;
              remoteAudio.play().catch(() => {});
              document.removeEventListener('click', unlockAudio);
              document.removeEventListener('touchstart', unlockAudio);
            };
            document.addEventListener('click', unlockAudio, { once: true });
            document.addEventListener('touchstart', unlockAudio, { once: true });
          });
        }

        // Web Audio API destination bridge to ensure audio flows into mobile speaker/earpiece
        try {
          const AudioCtx = window.AudioContext || window.webkitAudioContext;
          if (AudioCtx) {
            if (!audioContext || audioContext.state === 'closed') {
              audioContext = new AudioCtx();
            }
            if (audioContext.state === 'suspended') {
              audioContext.resume().catch(() => {});
            }
            const source = audioContext.createMediaStreamSource(incomingAudioStream);
            source.connect(audioContext.destination);
          }
        } catch(audioBridgeErr) {
          console.warn('Audio destination bridge notice:', audioBridgeErr);
        }
      }
      markCallConnected(activeCallSession ? activeCallSession.connected_at : null);
    };

    // Connection State Listeners
    peerConnection.onconnectionstatechange = function() {
      console.log('PeerConnection connectionState:', peerConnection.connectionState);
      if (peerConnection.connectionState === 'connected') {
        markCallConnected(activeCallSession ? activeCallSession.connected_at : null);
      } else if (peerConnection.connectionState === 'disconnected' || peerConnection.connectionState === 'failed') {
        const statusEl = document.getElementById('activeVoiceStatusText');
        if (statusEl && !isCallTimerRunning) statusEl.innerText = 'Reconnecting encrypted line...';
      }
    };

    peerConnection.oniceconnectionstatechange = function() {
      console.log('ICE connectionState:', peerConnection.iceConnectionState);
      if (peerConnection.iceConnectionState === 'connected' || peerConnection.iceConnectionState === 'completed') {
        markCallConnected(activeCallSession ? activeCallSession.connected_at : null);
      }
    };
  }

  // In-Call DataChannel (Peer-to-peer live chat)
  function initDataChannel() {
    if (!peerConnection) return;
    try {
      dataChannel = peerConnection.createDataChannel('in_call_chat', {
        negotiated: true,
        id: 0
      });
      dataChannel.onmessage = function(event) {
        try {
          const payload = JSON.parse(event.data);
          appendInCallMessage(payload.sender, payload.text, false, payload.time);
          const drawer = document.getElementById('webrtcInCallChatDrawer');
          if (drawer && drawer.classList.contains('d-none')) {
            const badge = document.getElementById('inCallChatUnreadBadge');
            if (badge) {
              const count = (parseInt(badge.innerText) || 0) + 1;
              badge.innerText = count;
              badge.classList.remove('d-none');
            }
          }
        } catch(e) {}
      };
    } catch(e) {
      console.warn('DataChannel init error:', e);
    }
  }

  function sendInCallMessage(text) {
    if (!text || !text.trim()) return;
    const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const payload = {
      sender: currentUserName,
      text: text.trim(),
      time: nowTime
    };

    if (dataChannel && dataChannel.readyState === 'open') {
      try {
        dataChannel.send(JSON.stringify(payload));
      } catch(e) {
        console.warn('DataChannel send error:', e);
      }
    }

    appendInCallMessage('You', payload.text, true, nowTime);
  }

  function appendInCallMessage(sender, text, isMine, timeStr) {
    const list = document.getElementById('inCallChatMessagesList');
    if (!list) return;

    const time = timeStr || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const bubble = document.createElement('div');
    bubble.className = isMine ? 'in-call-msg-mine' : 'in-call-msg-theirs';
    
    const div = document.createElement('div');
    div.innerText = text;
    const sanitizedText = div.innerHTML;
    const linkedText = sanitizedText.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" style="color: #fecc56; text-decoration: underline;">$1</a>');

    const displayName = isMine ? 'You' : $('<div>').text(sender).html();
    bubble.innerHTML = `
      <div style="font-size: 10px; opacity: 0.75; margin-bottom: 2px;">${displayName} • ${time}</div>
      <div style="line-height: 1.35;">${linkedText}</div>
    `;

    list.appendChild(bubble);
    list.scrollTop = list.scrollHeight;
  }

  // In-Call Screen Sharing (Universal Desktop & Mobile Hardened)
  async function toggleScreenShare() {
    if (!peerConnection) {
      alert('Active call connection required to share screen.');
      return;
    }

    if (isScreenSharing) {
      stopScreenShare();
      return;
    }

    const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    // 1. Check if getDisplayMedia is supported
    if (navigator.mediaDevices && typeof navigator.mediaDevices.getDisplayMedia === 'function') {
      try {
        // IMPORTANT: Never pass desktop-only constraints like cursor or displaySurface on mobile!
        const displayConstraints = isMobile
          ? { video: true, audio: false }
          : { video: { cursor: 'always', frameRate: { max: 20 } }, audio: false };

        screenStream = await navigator.mediaDevices.getDisplayMedia(displayConstraints);
      } catch(err) {
        if (err.name === 'NotAllowedError') {
          return; // User cancelled prompt
        }
        console.warn('getDisplayMedia attempt notice:', err);
        if (!isMobile) {
          alert('Could not start screen sharing: ' + (err.message || 'Permission denied'));
          return;
        }
      }
    }

    // 2. Smart Mobile Fallback (e.g. iOS Safari which restricts web screen capture)
    if (!screenStream && isMobile) {
      const useCam = confirm('Live screen recording is restricted by your mobile operating system browser. Would you like to share your camera feed to display documents or tax paperwork instead?');
      if (!useCam) return;
      try {
        screenStream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: { ideal: 'environment' } },
          audio: false
        });
      } catch(err) {
        alert('Could not access camera: ' + (err.message || 'Permission denied'));
        return;
      }
    }

    if (!screenStream) {
      alert('Screen sharing is not supported by this browser. Please use Chrome on Android, or Chrome/Edge/Safari on desktop.');
      return;
    }

    const screenTrack = screenStream.getVideoTracks()[0];
    if (!screenTrack) return;

    // Optimize text/document rendering quality
    if ('contentHint' in screenTrack) {
      screenTrack.contentHint = 'detail';
    }

    screenTrack.onended = function() {
      stopScreenShare();
    };

    const senders = peerConnection.getSenders();
    let videoSender = senders.find(s => s.track && s.track.kind === 'video');
    if (!videoSender) {
      videoSender = senders.find(s => !s.track || s.track.kind === 'video');
    }

    if (videoSender) {
      originalCameraTrack = videoSender.track;
      await videoSender.replaceTrack(screenTrack);

      // Bandwidth hardening: 1.5 Mbps bitrate limit with 15 fps max for smooth text without heavy internet delay
      try {
        if (videoSender.getParameters) {
          const params = videoSender.getParameters();
          if (!params.encodings || params.encodings.length === 0) {
            params.encodings = [{}];
          }
          params.encodings[0].maxBitrate = 1500000; // 1.5 Mbps
          params.encodings[0].maxFramerate = 15;
          await videoSender.setParameters(params);
        }
      } catch(e) {
        console.warn('Could not set encoding params:', e);
      }
    } else {
      peerConnection.addTrack(screenTrack, screenStream);
    }

    const localVid = document.getElementById('webrtcLocalVideo');
    if (localVid) {
      localVid.srcObject = screenStream;
      localVid.muted = true;
      localVid.play().catch(() => {});
    }

    document.getElementById('webrtcVoiceView')?.classList.add('d-none');
    document.getElementById('webrtcVideoView')?.classList.remove('d-none');

    isScreenSharing = true;
    const btn = document.getElementById('btnToggleScreenShare');
    if (btn) {
      btn.classList.add('active');
      btn.title = 'Stop Sharing Screen';
    }

    appendInCallMessage('System', '🖥️ Screen sharing started', true);
  }

  async function stopScreenShare() {
    if (!isScreenSharing) return;

    if (screenStream) {
      screenStream.getTracks().forEach(t => t.stop());
      screenStream = null;
    }

    if (peerConnection && originalCameraTrack) {
      const senders = peerConnection.getSenders();
      const videoSender = senders.find(s => s.track && s.track.kind === 'video');
      if (videoSender) {
        await videoSender.replaceTrack(originalCameraTrack);
      }
      const localVid = document.getElementById('webrtcLocalVideo');
      if (localVid && localMediaStream) {
        localVid.srcObject = localMediaStream;
        localVid.play().catch(() => {});
      }
    }

    isScreenSharing = false;
    const btn = document.getElementById('btnToggleScreenShare');
    if (btn) {
      btn.classList.remove('active');
      btn.title = 'Share Screen';
    }

    if (activeCallSession && activeCallSession.call_type === 'audio' && (!localMediaStream || localMediaStream.getVideoTracks().length === 0)) {
      document.getElementById('webrtcVoiceView')?.classList.remove('d-none');
      document.getElementById('webrtcVideoView')?.classList.add('d-none');
    }

    appendInCallMessage('System', 'Screen sharing stopped', true);
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
    callSeconds = 0;
    const timerBadgeInit = document.getElementById('activeCallTimer');
    if (timerBadgeInit) timerBadgeInit.innerText = '00:00';

    // Pre-prime remote audio playback during caller user gesture
    const remoteAudio = document.getElementById('webrtcRemoteAudio');
    if (remoteAudio) {
      remoteAudio.muted = false;
      remoteAudio.volume = 1.0;
      remoteAudio.play().catch(() => {});
    }

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
            markCallConnected(call.connected_at);
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

        // If connection is established on server, mark connected & ensure timer is running
        if (call.status === 'connected') {
          markCallConnected(call.connected_at);
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
      remoteAudio.muted = false;
      remoteAudio.volume = 1.0;
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

      // 4. Wait up to 450ms for initial STUN/host candidates to be collected into answer SDP
      await waitForIceGathering(peerConnection, 450);

      // 5. Send Answer to server
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
          markCallConnected(res.call ? res.call.connected_at : Math.floor(Date.now() / 1000));
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
    const finalDurationSec = callSeconds;
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
          action: 'end',
          duration_seconds: finalDurationSec
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

    const remoteAudio = document.getElementById('webrtcRemoteAudio');
    if (remoteAudio) {
      try { remoteAudio.pause(); } catch(e) {}
      remoteAudio.srcObject = null;
    }
    const remoteVideo = document.getElementById('webrtcRemoteVideo');
    if (remoteVideo) {
      try { remoteVideo.pause(); } catch(e) {}
      remoteVideo.srcObject = null;
    }

    if (isScreenSharing) {
      stopScreenShare();
    }
    if (dataChannel) {
      try { dataChannel.close(); } catch(e) {}
      dataChannel = null;
    }
    const chatDrawer = document.getElementById('webrtcInCallChatDrawer');
    if (chatDrawer) {
      chatDrawer.classList.add('d-none');
      chatDrawer.style.display = 'none';
    }
    const unreadBadge = document.getElementById('inCallChatUnreadBadge');
    if (unreadBadge) {
      unreadBadge.innerText = '0';
      unreadBadge.classList.add('d-none');
    }
    const btnChat = document.getElementById('btnToggleCallChat');
    if (btnChat) btnChat.classList.remove('active');
    const msgList = document.getElementById('inCallChatMessagesList');
    if (msgList) {
      msgList.innerHTML = '<div class="text-muted small text-center my-1" style="font-size: 11px; opacity: 0.8;">🔒 Encrypted peer-to-peer live in-call channel.</div>';
    }

    isCallMinimized = false;
    clearCallPersistence();
    const topBarEl = document.getElementById('webrtcTopPinnedCallBar');
    if (topBarEl) topBarEl.style.display = 'none';
    document.body.classList.remove('modal-open');
    document.querySelectorAll('.modal-backdrop').forEach(function(b) {
      b.style.display = '';
    });

    $('#activeWebRtcCallModal').modal('hide');
    $('#incomingCallModal').modal('hide');
    activeCallSession = null;
    isCaller = false;
    pendingLocalCandidates = [];
    addedCandidateKeys.clear();
    isCallTimerRunning = false;
    callSeconds = 0;
    const timerBadgeEnd = document.getElementById('activeCallTimer');
    if (timerBadgeEnd) timerBadgeEnd.innerText = '00:00';
    const pipTimerEnd = document.getElementById('pipCallTimer');
    if (pipTimerEnd) pipTimerEnd.innerText = '00:00';
  }

  // Call Session Persistence Helpers across page loads / switches
  function saveCallPersistence(isMinimized) {
    if (!activeCallSession || !activeCallSession.call_id) return;
    try {
      const state = {
        call_id: activeCallSession.call_id,
        call_type: activeCallSession.call_type || 'audio',
        caller_name: activeCallSession.caller_name || '',
        recipient_name: activeCallSession.recipient_name || '',
        caller_id: activeCallSession.caller_id,
        recipient_id: activeCallSession.recipient_id,
        caller_avatar: activeCallSession.caller_avatar || '',
        status: activeCallSession.status || 'connected',
        connected_at: activeCallSession.connected_at || Math.floor(Date.now() / 1000),
        isMinimized: !!isMinimized,
        peerName: document.getElementById('activeCallParticipantName')?.innerText || 'Participant',
        timestamp: Date.now()
      };
      sessionStorage.setItem('webrtc_call_persisted_state', JSON.stringify(state));
    } catch(e) {}
  }

  function clearCallPersistence() {
    try {
      sessionStorage.removeItem('webrtc_call_persisted_state');
    } catch(e) {}
  }

  // PiP Call Window Minimization (WhatsApp & FaceTime Top-Pinned Dynamic Island Style)
  let isCallMinimized = false;
  let lastMinimizeToggleTime = 0;

  function toggleCallMinimize(forceState) {
    const now = Date.now();
    if (now - lastMinimizeToggleTime < 250) return;
    lastMinimizeToggleTime = now;

    if (typeof forceState === 'boolean') {
      isCallMinimized = forceState;
    } else {
      isCallMinimized = !isCallMinimized;
    }

    const modalEl = document.getElementById('activeWebRtcCallModal');
    const topBarEl = document.getElementById('webrtcTopPinnedCallBar');

    if (isCallMinimized) {
      // Sync peer name, timer and call type badge to top-pinned bar
      const peerNameEl = document.getElementById('activeCallParticipantName');
      const pipPeerName = document.getElementById('pipCallPeerName');
      if (pipPeerName && peerNameEl) {
        pipPeerName.innerText = peerNameEl.innerText;
      }
      const activeTimer = document.getElementById('activeCallTimer');
      const pipTimer = document.getElementById('pipCallTimer');
      if (pipTimer && activeTimer) {
        pipTimer.innerText = activeTimer.innerText;
      }
      const modeBadge = document.getElementById('activeCallModeBadge');
      const pipBadge = document.getElementById('pipCallBadge');
      if (pipBadge && modeBadge) {
        pipBadge.innerHTML = modeBadge.innerHTML;
      }

      // Hide modal completely and unlock body scrolling
      if (modalEl) {
        modalEl.style.display = 'none';
        modalEl.classList.remove('show');
      }
      document.body.classList.remove('modal-open');
      document.querySelectorAll('.modal-backdrop').forEach(function(b) {
        b.style.display = 'none';
      });

      // Show top-pinned dynamic bar
      if (topBarEl) {
        topBarEl.style.display = 'flex';
      }
      saveCallPersistence(true);
    } else {
      // Hide top bar
      if (topBarEl) {
        topBarEl.style.display = 'none';
      }

      // Restore full modal window
      if (modalEl) {
        modalEl.style.display = 'block';
        modalEl.classList.add('show');
      }
      document.body.classList.add('modal-open');
      document.querySelectorAll('.modal-backdrop').forEach(function(b) {
        b.style.display = '';
      });
      saveCallPersistence(false);
    }
  }
  window.toggleCallMinimize = toggleCallMinimize;

  // Auto-restore persisted live call on page switch / browser reload
  (function restorePersistedCall() {
    try {
      const raw = sessionStorage.getItem('webrtc_call_persisted_state');
      if (!raw) return;
      const state = JSON.parse(raw);
      if (state && state.call_id && (Date.now() - (state.timestamp || 0) < 3600000)) {
        activeCallSession = state;
        isCaller = (state.caller_id === currentUserId);
        const otherPartyName = state.peerName || (isCaller ? state.recipient_name : state.caller_name) || 'Counsel';
        updateCallModalUi(state.call_type, otherPartyName, state.caller_avatar);

        const elapsedSec = Math.max(0, Math.floor(Date.now() / 1000) - (state.connected_at || Math.floor(Date.now() / 1000)));
        startCallTimer(elapsedSec);

        if (state.isMinimized) {
          toggleCallMinimize(true);
        } else {
          $('#activeWebRtcCallModal').modal('show');
        }

        reconnectPeerConnection(state);
      }
    } catch(e) {
      console.warn('Call persistence restore notice:', e);
    }
  })();

  // Global Audio Chime (Synthesizer via Web Audio API - Zero external dependencies)
  function playMessageChime() {
    try {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (!AudioCtx) return;
      if (!audioContext) {
        audioContext = new AudioCtx();
      }
      if (audioContext.state === 'suspended') {
        audioContext.resume().catch(() => {});
      }
      const ctx = audioContext;

      const now = ctx.currentTime;
      // Tone 1: C5 (523.25 Hz)
      const osc1 = ctx.createOscillator();
      const gain1 = ctx.createGain();
      osc1.type = 'sine';
      osc1.frequency.setValueAtTime(523.25, now);
      gain1.gain.setValueAtTime(0.22, now);
      gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
      osc1.connect(gain1);
      gain1.connect(ctx.destination);
      osc1.start(now);
      osc1.stop(now + 0.35);

      // Tone 2: E5 (659.25 Hz) slightly delayed
      const osc2 = ctx.createOscillator();
      const gain2 = ctx.createGain();
      osc2.type = 'sine';
      osc2.frequency.setValueAtTime(659.25, now + 0.12);
      gain2.gain.setValueAtTime(0.25, now + 0.12);
      gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
      osc2.connect(gain2);
      gain2.connect(ctx.destination);
      osc2.start(now + 0.12);
      osc2.stop(now + 0.55);
    } catch(e) {}
  }

  let lastNotifiedMsgId = null;
  let originalPageTitle = document.title;
  let msgTitleFlashInterval = null;

  function handleIncomingUnreadMessage(msg) {
    if (!msg || !msg.id) return;
    if (msg.id === lastNotifiedMsgId) return;
    lastNotifiedMsgId = msg.id;

    const isOnChatPage = window.location.pathname.includes('/conversation') ||
                         window.location.pathname.includes('/messages') ||
                         window.location.pathname.includes('/chat');

    // If already viewing chat actively, avoid duplicate popup
    if (isOnChatPage && !document.hidden) {
      return;
    }

    // 1. Play message chime
    playMessageChime();

    // 2. Flash browser page title
    if (!msgTitleFlashInterval) {
      let isAlt = false;
      msgTitleFlashInterval = setInterval(function() {
        if (!document.hidden && isOnChatPage) {
          clearInterval(msgTitleFlashInterval);
          msgTitleFlashInterval = null;
          document.title = originalPageTitle;
          return;
        }
        document.title = isAlt ? '💬 (' + (msg.sender_name || '1') + ') New Message!' : originalPageTitle;
        isAlt = !isAlt;
      }, 1200);
    }

    // 3. System Push / Desktop Notification (Works on Android Mobile & Desktop)
    showDesktopNotification('💬 ' + msg.sender_name, msg.text, msg.url);

    // 4. In-page Toast Banner Alert
    const toast = document.getElementById('globalMessageToast');
    if (toast) {
      document.getElementById('globalToastSender').innerText = msg.sender_name;
      document.getElementById('globalToastTime').innerText = msg.time || 'Just now';
      document.getElementById('globalToastText').innerText = msg.text;
      const replyBtn = document.getElementById('globalToastReplyBtn');
      if (replyBtn) {
        replyBtn.href = msg.url;
      }
      toast.style.display = 'block';
      setTimeout(function() {
        if (toast.style.display === 'block') {
          toast.style.display = 'none';
        }
      }, 9000);
    }
  }

  // 13. Global Call & Message Check Loop (with Web Worker to prevent mobile background throttling)
  let lastRingingNotifTime = 0;

  function handleCallPollResponse(res) {
    if (!res) return;

    // Handle unread messages globally across all pages
    if (res.latest_unread) {
      handleIncomingUnreadMessage(res.latest_unread);
    }

    const call = res.active_call;

    // A. Auto-mount and recover active connected call across page switches or browser reloads
    if (call && call.status === 'connected' && (call.recipient_id === currentUserId || call.caller_id === currentUserId)) {
      if (!activeCallSession) {
        activeCallSession = call;
        isCaller = (call.caller_id === currentUserId);
        const otherPartyName = isCaller ? (call.recipient_name || 'Participant') : (call.caller_name || 'Counsel');

        updateCallModalUi(call.call_type, otherPartyName, call.caller_avatar);

        const elapsedSec = Math.max(0, Math.floor(Date.now() / 1000) - (call.connected_at || call.created_at || Math.floor(Date.now() / 1000)));
        startCallTimer(elapsedSec);

        // Keep top-pinned bar alive and functional
        toggleCallMinimize(true);
        reconnectPeerConnection(call);
      }
      return;
    }

    // B. Handle transient empty poll responses vs explicit call termination
    if (!call) {
      consecutiveEmptyCallPolls++;
      // If currently engaged in a connected live call, tolerate up to 4 consecutive empty polls before hanging up
      if (activeCallSession && activeCallSession.status === 'connected' && consecutiveEmptyCallPolls <= 4) {
        return;
      }
      if ($('#incomingCallModal').hasClass('show')) {
        stopTelephoneRing();
        stopTitleFlash();
        $('#incomingCallModal').modal('hide');
      }
      if (activeCallSession && activeCallSession.status === 'connected') {
        hangUpCall(false);
      }
      return;
    }
    consecutiveEmptyCallPolls = 0;

    // Call was explicitly ended, declined, missed, or cancelled on server
    if (inArray(['ended', 'declined', 'missed', 'cancelled'], call.status)) {
      if ($('#incomingCallModal').hasClass('show')) {
        stopTelephoneRing();
        stopTitleFlash();
        $('#incomingCallModal').modal('hide');
      }
      if (activeCallSession && activeCallSession.status === 'connected') {
        hangUpCall(false);
      }
      return;
    }

    // C. Handle incoming ringing call
    if (call.status === 'ringing' && call.recipient_id === currentUserId) {
      activeCallSession = call;

      const isVideo = (call.call_type === 'video');
      const callerName = call.caller_name || 'Counsel';
      document.getElementById('incomingCallerName').innerText = callerName;
      document.getElementById('incomingCallTypeBadge').innerHTML = isVideo
        ? '<i class="fas fa-video mr-1"></i> Incoming Video Call'
        : '<i class="fas fa-phone-alt mr-1"></i> Incoming Voice Call';

      const initial = callerName.charAt(0).toUpperCase();
      document.getElementById('incomingCallerInitial').innerText = initial;

      if (!$('#incomingCallModal').hasClass('show') && !$('#activeWebRtcCallModal').hasClass('show') && !isCallMinimized) {
        playTelephoneRing();
        startTitleFlash('Incoming ' + (isVideo ? 'Video' : 'Voice') + ' Call (' + callerName + ')...');
        showDesktopNotification('Incoming ' + (isVideo ? 'Video' : 'Voice') + ' Call', callerName + ' is calling you...');
        $('#incomingCallModal').modal('show');
        lastRingingNotifTime = Date.now();
      } else if (document.hidden && (Date.now() - lastRingingNotifTime > 3200)) {
        // Repeatedly re-notify & vibrate off-browser tab in rhythm with telephone ring cadence
        showDesktopNotification('Incoming ' + (isVideo ? 'Video' : 'Voice') + ' Call', callerName + ' is calling you...');
        lastRingingNotifTime = Date.now();
      }
    }
  }

  async function reconnectPeerConnection(call) {
    try {
      await acquireMediaStream(call.call_type);
      setupPeerConnection();

      if (isCaller) {
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        fetch("{{ route('portal.call.signal') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            call_id: call.call_id,
            action: 'reconnect_offer',
            offer: offer
          })
        }).catch(() => {});
      } else {
        fetch("{{ route('portal.call.signal') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            call_id: call.call_id,
            action: 'poll'
          })
        }).catch(() => {});
      }
      pollSignalingUpdates(call.call_id);
    } catch(e) {
      console.warn('Call recovery media warning:', e);
    }
  }

  function initBackgroundCallWatcher() {
    if (currentUserId <= 0) return;

    function pollCheck() {
      fetch("{{ route('portal.call.check') }}", {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(res => handleCallPollResponse(res))
      .catch(() => {});
    }

    // Try unthrottled Web Worker (immune to mobile background timer throttling)
    try {
      const blobCode = "setInterval(function() { postMessage('tick'); }, 2500);";
      const blob = new Blob([blobCode], { type: 'application/javascript' });
      const worker = new Worker(URL.createObjectURL(blob));
      worker.onmessage = function() {
        pollCheck();
      };
    } catch(e) {
      setInterval(pollCheck, 2500);
    }
  }
  initBackgroundCallWatcher();

  // 14. In-Portal Seamless Navigation (Preserves active WebRTC calls across page switches)
  function isCallActiveOrMinimized() {
    return (activeCallSession && (activeCallSession.status === 'connected' || activeCallSession.status === 'ringing')) || isCallMinimized;
  }

  async function navigateSeamlesslyDuringCall(targetUrl) {
    try {
      saveCallPersistence(isCallMinimized);

      let contentContainer = document.querySelector('#wrapper-content') ||
                             document.querySelector('.client-content-container') ||
                             document.querySelector('.client-content-col') ||
                             document.querySelector('.staff-content-container') ||
                             document.querySelector('.staff-content-col') ||
                             document.querySelector('.main-content') ||
                             document.querySelector('#main-content') ||
                             document.querySelector('.content-wrapper');

      if (!contentContainer) {
        window.location.href = targetUrl;
        return;
      }

      contentContainer.style.transition = 'opacity 0.15s ease';
      contentContainer.style.opacity = '0.5';

      const res = await fetch(targetUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!res.ok) {
        window.location.href = targetUrl;
        return;
      }

      const html = await res.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');

      let newContent = doc.querySelector('#wrapper-content') ||
                        doc.querySelector('.client-content-container') ||
                        doc.querySelector('.client-content-col') ||
                        doc.querySelector('.staff-content-container') ||
                        doc.querySelector('.staff-content-col') ||
                        doc.querySelector('.main-content') ||
                        doc.querySelector('#main-content') ||
                        doc.querySelector('.content-wrapper');

      if (newContent) {
        contentContainer.innerHTML = newContent.innerHTML;
        contentContainer.style.opacity = '1';

        if (doc.title) {
          originalPageTitle = doc.title;
          originalDocTitle = doc.title;
          document.title = doc.title;
        }

        window.history.pushState({ seamless: true, url: targetUrl }, '', targetUrl);
        updateSidebarActiveNav(targetUrl);

        // Execute new scripts
        const scripts = contentContainer.querySelectorAll('script');
        scripts.forEach(oldScript => {
          const newScript = document.createElement('script');
          Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
          newScript.appendChild(document.createTextNode(oldScript.innerHTML));
          oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });

        const topBarEl = document.getElementById('webrtcTopPinnedCallBar');
        if (topBarEl && isCallMinimized) {
          topBarEl.style.display = 'flex';
        }
      } else {
        window.location.href = targetUrl;
      }
    } catch (err) {
      console.warn('Seamless navigation error during call:', err);
      window.location.href = targetUrl;
    }
  }

  function updateSidebarActiveNav(targetUrl) {
    try {
      const parsedTarget = new URL(targetUrl, window.location.origin).pathname;
      document.querySelectorAll('#ifwClientSidebar li, .ifw-nav-list li, .staff-nav-list li').forEach(li => {
        const link = li.querySelector('a');
        if (link && link.href) {
          const linkPath = new URL(link.href, window.location.origin).pathname;
          if (parsedTarget === linkPath || (linkPath !== '/' && parsedTarget.startsWith(linkPath))) {
            li.classList.add('active');
          } else {
            li.classList.remove('active');
          }
        }
      });
    } catch(e) {}
  }

  // Intercept in-portal link clicks when call is active
  document.addEventListener('click', function(e) {
    if (!isCallActiveOrMinimized()) return;

    const link = e.target.closest('a');
    if (!link || !link.href) return;

    const rawHref = link.getAttribute('href') || '';
    if (rawHref.startsWith('#') || rawHref.startsWith('javascript:') || rawHref.startsWith('tel:') || rawHref.startsWith('mailto:')) return;
    if (link.target === '_blank' || link.hasAttribute('download')) return;

    try {
      const url = new URL(link.href, window.location.origin);
      if (url.origin !== window.location.origin) return;
      if (url.pathname.includes('/logout') || url.pathname.includes('/download')) return;

      if (url.pathname.startsWith('/client') || url.pathname.startsWith('/staff') || url.pathname.startsWith('/admin')) {
        e.preventDefault();
        e.stopPropagation();
        navigateSeamlesslyDuringCall(url.href);
      }
    } catch(e) {}
  }, true);

  window.addEventListener('popstate', function(e) {
    if (isCallActiveOrMinimized()) {
      navigateSeamlesslyDuringCall(window.location.href);
    }
  });

  // Accidental close protection during live calls
  window.addEventListener('beforeunload', function(e) {
    if (activeCallSession && (activeCallSession.status === 'connected' || activeCallSession.status === 'ringing')) {
      e.preventDefault();
      e.returnValue = 'You are currently in an active consultation call. Leaving will end the call.';
      return e.returnValue;
    }
  });

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

    const btnMin = document.getElementById('btnMinimizeCall');
    if (btnMin) {
      btnMin.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggleCallMinimize(true);
      });
    }

    // One-time prompt for browser notifications on first user gesture
    window.addEventListener('click', function reqPerm() {
      if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
      }
      window.removeEventListener('click', reqPerm);
    }, { once: true });

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

    // Screen Share Toggle
    const btnScreen = document.getElementById('btnToggleScreenShare');
    if (btnScreen) {
      btnScreen.addEventListener('click', toggleScreenShare);
    }

    // In-Call Chat Drawer Toggle
    const btnChat = document.getElementById('btnToggleCallChat');
    const chatDrawer = document.getElementById('webrtcInCallChatDrawer');
    const btnCloseDrawer = document.getElementById('btnCloseInCallChat');
    const unreadBadge = document.getElementById('inCallChatUnreadBadge');

    if (btnChat && chatDrawer) {
      btnChat.addEventListener('click', function() {
        const isHidden = chatDrawer.classList.contains('d-none');
        if (isHidden) {
          chatDrawer.classList.remove('d-none');
          chatDrawer.style.display = 'flex';
          this.classList.add('active');
          if (unreadBadge) {
            unreadBadge.innerText = '0';
            unreadBadge.classList.add('d-none');
          }
          setTimeout(() => document.getElementById('inCallChatInput')?.focus(), 80);
        } else {
          chatDrawer.classList.add('d-none');
          chatDrawer.style.display = 'none';
          this.classList.remove('active');
        }
      });
    }

    if (btnCloseDrawer && chatDrawer) {
      btnCloseDrawer.addEventListener('click', function() {
        chatDrawer.classList.add('d-none');
        chatDrawer.style.display = 'none';
        if (btnChat) btnChat.classList.remove('active');
      });
    }

    // In-Call Chat Form Submit
    const inCallForm = document.getElementById('inCallChatForm');
    const inCallInput = document.getElementById('inCallChatInput');
    if (inCallForm && inCallInput) {
      inCallForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const txt = inCallInput.value.trim();
        if (txt) {
          sendInCallMessage(txt);
          inCallInput.value = '';
        }
      });
    }

    // In-Call Quick Emoji buttons
    document.querySelectorAll('.in-call-emoji-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const emoji = this.innerText;
        if (inCallInput) {
          inCallInput.value += emoji;
          inCallInput.focus();
        }
      });
    });

    // Top Pinned Floating Call Bar Actions
    const pipBtnMute = document.getElementById('pipBtnMute');
    if (pipBtnMute) {
      pipBtnMute.addEventListener('click', function(e) {
        e.stopPropagation();
        if (localMediaStream) {
          const track = localMediaStream.getAudioTracks()[0];
          if (track) {
            track.enabled = !track.enabled;
            const btnAudio = document.getElementById('btnToggleAudio');
            if (track.enabled) {
              this.innerHTML = '<i class="fas fa-microphone" style="font-size: 12px;"></i>';
              this.style.background = '#1e293b';
              if (btnAudio) {
                btnAudio.classList.remove('active');
                btnAudio.innerHTML = '<i class="fas fa-microphone"></i>';
              }
            } else {
              this.innerHTML = '<i class="fas fa-microphone-slash" style="font-size: 12px; color: #f87171;"></i>';
              this.style.background = '#7f1d1d';
              if (btnAudio) {
                btnAudio.classList.add('active');
                btnAudio.innerHTML = '<i class="fas fa-microphone-slash"></i>';
              }
            }
          }
        }
      });
    }

    const pipBtnHangup = document.getElementById('pipBtnHangup');
    if (pipBtnHangup) {
      pipBtnHangup.addEventListener('click', function(e) {
        e.stopPropagation();
        hangUpCall(true);
      });
    }

    // Register Service Worker for Android Chrome & mobile push notifications
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/sw.js').catch(function() {});
    }
  });
})();
</script>
