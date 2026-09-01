@extends('frontend.theme1.auth-client.layouts.master-layout')

@section('title', config('app.name', 'Your CPA Expert') . ' | Live Support & Chat')

@section('page-css')
<style>
    /* IFW-REPLICA FULL-PAGE CHAT SYSTEM */
    .chat-portal-card {
        background: #11151e; border: 1px solid #28303f; border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4); overflow: hidden;
        display: flex; flex-direction: column;
        height: calc(100vh - 170px); min-height: 580px;
    }
    .chat-portal-header { background: #161a23; border-bottom: 1px solid #28303f; padding: 14px 20px; flex-shrink: 0; }
    .chatwoot-docked-container { position: relative !important; flex: 1; width: 100% !important; overflow: hidden !important; background: #000; }
    .woot--bubble-holder, .woot-widget-bubble { display: none !important; opacity: 0 !important; pointer-events: none !important; }
    .woot-widget-holder {
        position: absolute !important; top: 0 !important; left: 0 !important;
        width: 100% !important; max-width: 100% !important; height: 100% !important; max-height: 100% !important;
        margin: 0 !important; padding: 0 !important; border-radius: 0 !important;
        box-shadow: none !important; transform: none !important;
        display: block !important; visibility: visible !important; opacity: 1 !important; z-index: 5 !important;
    }
    .woot-widget-holder iframe, #chatwoot_live_chat_widget {
        position: absolute !important; top: 0 !important; left: 0 !important;
        width: 100% !important; height: 100% !important; border: none !important; border-radius: 0 !important; background: #000 !important;
    }
    #chatwootLoadingPh {
        position: absolute; inset: 0; display: flex; flex-direction: column;
        align-items: center; justify-content: center; background: #0a0c10; z-index: 10; pointer-events: none;
    }
    .chat-messages-body {
        flex: 1; overflow-y: auto; padding: 20px; background: #0a0c10;
        display: flex; flex-direction: column; gap: 14px; scroll-behavior: smooth;
    }
    .chat-bubble { max-width: 75%; padding: 12px 16px; border-radius: 12px; font-size: 13.5px; line-height: 1.45; word-break: break-word; }
    .chat-bubble-in { align-self: flex-start; background: #181d27; color: #f1f5f9; border: 1px solid #28303f; border-bottom-left-radius: 2px; }
    .chat-bubble-out { align-self: flex-end; background: linear-gradient(135deg, #fecc56, #f0a500); color: #000 !important; border-bottom-right-radius: 2px; font-weight: 500; }
    .chat-bubble-time { font-size: 10.5px; color: #94a3b8; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
    .chat-bubble-out .chat-bubble-time { justify-content: flex-end; color: #5a4200; }
    .chat-attachment-box { background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 6px 10px; margin-top: 6px; display: inline-flex; align-items: center; gap: 8px; }
    .chat-footer-native { background: #161a23; border-top: 1px solid #28303f; padding: 14px 20px; flex-shrink: 0; }
    .chat-input-control { background: #0f131a !important; border: 1px solid #28303f !important; color: #ffffff !important; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; }
    .chat-input-control:focus { border-color: #fecc56 !important; box-shadow: 0 0 0 2px rgba(254,204,86,0.2) !important; }
    .btn-gold { background: linear-gradient(135deg, #fecc56, #f0a500); color: #000 !important; font-weight: 700; border: none; border-radius: 8px; padding: 10px 20px; transition: all 0.2s; }
    .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(254,204,86,0.45); }
    body.light-mode .chat-portal-card, html.light-mode .chat-portal-card { background: #ffffff !important; border-color: #e2e8f0 !important; }
    body.light-mode .chat-portal-header, html.light-mode .chat-portal-header { background: #f8fafc !important; border-bottom-color: #e2e8f0 !important; }
    body.light-mode .chatwoot-docked-container, html.light-mode .chatwoot-docked-container { background: #ffffff !important; }
    body.light-mode #chatwootLoadingPh, html.light-mode #chatwootLoadingPh { background: #f8fafc !important; }
    body.light-mode .chat-messages-body, html.light-mode .chat-messages-body { background: #f8fafc !important; }
    body.light-mode .chat-bubble-in, html.light-mode .chat-bubble-in { background: #ffffff !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
    body.light-mode .chat-footer-native, html.light-mode .chat-footer-native { background: #ffffff !important; border-top-color: #e2e8f0 !important; }
    body.light-mode .chat-input-control, html.light-mode .chat-input-control { background: #f1f5f9 !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
    @media (max-width: 768px) {
        .chat-portal-card { height: calc(100dvh - 140px); border-radius: 0 !important; min-height: 0; }
        .chat-bubble { max-width: 92%; }
    }
</style>
@endsection

@section('content')
@php
    $chatSettings   = $chatSettings ?? \App\Services\ChatwootService::getSettings();
    $provider       = $chatSettings['provider'] ?? 'chatwoot';
    $chatwootToken  = $chatSettings['website_token'] ?? '';
    $chatwootBase   = rtrim($chatSettings['base_url'] ?? 'https://app.chatwoot.com', '/');
    $tawktoId       = $chatSettings['tawkto_property_id'] ?? '';
    $u              = Auth::user();
    $clientName     = $u->name ?: 'Client #'.$u->id;
    $clientEmail    = $u->email;
    $clientPhone    = $u->phone ?? '';
    $avatarUrl      = $u->profile_photo_url ?? '';
    if ($avatarUrl && !str_starts_with($avatarUrl, 'http')) { $avatarUrl = url($avatarUrl); }
    $cwId           = 'client_'.$u->id;
    $cwHmac         = $hmacHash ?? (!empty($chatSettings['hmac_key']) ? hash_hmac('sha256', $cwId, $chatSettings['hmac_key']) : '');
    $counselName    = ($counsel->name ?? null) ?: 'Gary Livingston, Senior CPA & Legal Counsel';
@endphp

<div class="container-fluid px-0">
  <div class="chat-portal-card">

    {{-- HEADER --}}
    <div class="chat-portal-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mr-3 flex-shrink-0"
             style="width:44px;height:44px;background:rgba(254,204,86,0.15);color:#fecc56;font-size:18px;font-weight:bold;border:2px solid #fecc56;">
          {{ strtoupper(substr($counselName, 0, 1)) }}
        </div>
        <div>
          <h6 class="font-weight-bold text-white mb-0" style="font-size:15px;">{{ $counselName }}</h6>
          <div class="d-flex align-items-center small flex-wrap" style="gap:8px;">
            <span class="text-success font-weight-bold" style="font-size:11px;"><i class="fas fa-circle mr-1" style="font-size:8px;"></i>{{ __('Counsel Online') }}</span>
            <span class="text-muted">&bull;</span>
            <span class="text-warning font-weight-bold" style="font-size:11px;">CLI-{{ sprintf('%05d', $u->id) }}</span>
            @if($provider==='chatwoot')
              <span class="text-muted">&bull;</span>
              <span class="text-muted" style="font-size:11px;"><i class="fas fa-shield-alt text-success mr-1"></i>Chatwoot Encrypted</span>
            @endif
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center" style="gap:8px;">
        <span class="badge d-none d-sm-inline-block px-3 py-2"
              style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);font-size:11px;">
          <i class="fas fa-lock mr-1"></i>{{ __('256-Bit Encrypted') }}
        </span>
        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-secondary text-light px-3 font-weight-bold" style="border-color:#3b4252;">
          <i class="fas fa-arrow-left mr-1"></i>{{ __('Dashboard') }}
        </a>
      </div>
    </div>

    {{-- CHATWOOT --}}
    @if($provider==='chatwoot')
      @if(!empty($chatwootToken))
        <div class="chatwoot-docked-container" id="chatwootMountFrame">
          <div id="chatwootLoadingPh">
            <i class="fas fa-spinner fa-spin text-warning mb-3" style="font-size:3rem;"></i>
            <h5 class="text-warning">{{ __('Connecting to Secure Case Line...') }}</h5>
            <p class="text-muted small mb-0">{{ __('Synchronizing credentials & loading conversation history.') }}</p>
          </div>
        </div>
        <script>
        (function(){
          var BASE = @json($chatwootBase);
          var TOK  = @json($chatwootToken);
          var CID  = @json($cwId);
          var HMAC = @json($cwHmac);
          var NM   = @json($clientName);
          var EM   = @json($clientEmail);
          var PH   = @json($clientPhone);
          var AV   = @json($avatarUrl);
          var UID  = {{ (int)$u->id }};

          function isLight(){ return document.documentElement.classList.contains('light-mode')||document.body.classList.contains('light-mode'); }
          function bg(){ return isLight()?'#ffffff':'#000000'; }

          function dock(){
            // Move woot-widget-holder INTO our mount container if it isn't already
            var m=document.getElementById('chatwootMountFrame');
            if(m){
              var h=document.querySelector('.woot-widget-holder');
              if(h && h.parentNode !== m){
                m.appendChild(h);
              }
            }
            var m=document.getElementById('chatwootMountFrame'); if(!m) return;
            m.style.setProperty('background',bg(),'important');
            var h=document.querySelector('.woot-widget-holder');
            if(h){
              ['position:absolute','top:0','left:0','width:100%','max-width:100%','height:100%','max-height:100%',
               'margin:0','padding:0','border-radius:0','box-shadow:none','transform:none',
               'display:block','visibility:visible','opacity:1','z-index:5'].forEach(function(r){
                var p=r.split(':'); h.style.setProperty(p[0].trim(),p[1].trim(),'important');
              });
              h.style.setProperty('background',bg(),'important');
              var f=h.querySelector('iframe')||document.getElementById('chatwoot_live_chat_widget');
              if(f){
                ['position:absolute','top:0','left:0','width:100%','height:100%','min-height:100%','border:none','border-radius:0'].forEach(function(r){
                  var p=r.split(':'); f.style.setProperty(p[0].trim(),p[1].trim(),'important');
                });
                f.style.setProperty('background',bg(),'important');
              }
              var ph=document.getElementById('chatwootLoadingPh');
              if(ph) ph.style.display='none';
            }
          }

          function theme(){
            var t=isLight()?'light':'dark';
            if(window.$chatwoot){
              try{ window.$chatwoot.setDarkMode(t); }catch(e){}
              try{ window.$chatwoot.setTheme(t); }catch(e){}
            }
            dock();
          }

          window.chatwootSettings={hideMessageBubble:true,position:'right',locale:'en',type:'expanded_bubble',darkMode:isLight()?'light':'dark'};

          (function(d,t){
            var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=BASE+'/packs/js/sdk.js'; g.async=true; g.defer=true;
            s.parentNode.insertBefore(g,s);
            g.onload=function(){ if(window.chatwootSDK) window.chatwootSDK.run({websiteToken:TOK,baseUrl:BASE}); };
            g.onerror=function(){
              var ph=document.getElementById('chatwootLoadingPh');
              if(ph){ ph.innerHTML='<i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i><h5 class="text-warning">Chat Unavailable</h5><p class="text-muted small">Unable to reach chat server. Please contact support by email.</p>'; ph.style.pointerEvents='auto'; }
            };
          })(document,'script');

          window.addEventListener('chatwoot:ready',function(){
            if(!window.$chatwoot) return;
            var ud={name:NM,email:EM,avatar_url:AV,phone_number:PH};
            if(HMAC) ud.identifier_hash=HMAC;
            window.$chatwoot.setUser(CID,ud);
            window.$chatwoot.setCustomAttributes({client_id:String(UID),portal:'{{ config("app.name","Your CPA Expert") }} Client Portal',account_type:'Legal & CPA Client'});
            theme();
            window.$chatwoot.toggle('open');
            setTimeout(dock,200);
          });

          var fi=setInterval(dock,150);
          setTimeout(function(){ clearInterval(fi); setInterval(dock,1000); },10000);
          var mo=new MutationObserver(theme);
          mo.observe(document.documentElement,{attributes:true,attributeFilter:['class']});
          mo.observe(document.body,{attributes:true,attributeFilter:['class']});
        })();
        </script>
      @else
        <div class="flex-fill d-flex align-items-center justify-content-center p-5 text-center">
          <div>
            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
            <h5 class="text-warning">{{ __('Chatwoot Not Configured') }}</h5>
            <p class="text-muted small">{{ __('Go to Admin → Settings → Live Chat to enter your Chatwoot Website Token.') }}</p>
          </div>
        </div>
      @endif

    {{-- TAWK.TO --}}
    @elseif(in_array($provider,['tawkto','tawk']))
      @php
        $tk=trim(preg_replace(['/^.*tawk\.to\/chat\//i','/^.*embed\.tawk\.to\//i'],['',$tawktoId],''), " \t\n\r;'\"/");
        $tParts=explode('/',$tk); $tProp=$tParts[0]??''; $tHash=$tParts[1]??'default';
      @endphp
      @if($tProp)
        <div class="flex-fill" style="position:relative;">
          <iframe src="https://tawk.to/chat/{{ $tProp }}/{{ $tHash }}"
                  style="width:100%;height:100%;min-height:580px;border:none;display:block;background:#000;"
                  allow="camera;microphone;autoplay;encrypted-media;" title="{{ __('Live Support') }}"></iframe>
        </div>
      @else
        <div class="flex-fill d-flex align-items-center justify-content-center p-5 text-center">
          <div><i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i><h5 class="text-warning">{{ __('Tawk.to Not Configured') }}</h5></div>
        </div>
      @endif

    {{-- NATIVE FALLBACK --}}
    @else
      <div class="chat-messages-body" id="chatMessagesStream">
        @forelse($messages??[] as $msg)
          @php $mine=($msg->user_id===Auth::id()); @endphp
          <div class="chat-bubble {{ $mine?'chat-bubble-out':'chat-bubble-in' }}" id="msg-item-{{ $msg->id }}" data-msg-id="{{ $msg->id }}">
            @if(!$mine)<div class="font-weight-bold text-warning small mb-1" style="font-size:11px;">{{ $msg->user->name??'Assigned Counsel' }}</div>@endif
            @if(!empty($msg->text))<div>{{ $msg->text }}</div>@endif
            @if(!empty($msg->file))
              <div class="chat-attachment-box"><i class="fas fa-file-alt text-warning"></i><a href="{{ asset($msg->file) }}" target="_blank" class="text-white small font-weight-bold text-decoration-none">{{ $msg->file_name??__('Attachment') }}</a></div>
            @endif
            <div class="chat-bubble-time"><span>{{ $msg->created_at->format('h:i A') }}</span>@if($mine)<i class="fas fa-check-double text-warning ml-1" style="font-size:9px;"></i>@endif</div>
          </div>
        @empty
          <div id="noMessagesPlaceholder" class="p-5 text-center text-muted my-auto">
            <div style="width:60px;height:60px;border-radius:50%;background:rgba(254,204,86,0.1);border:2px solid rgba(254,204,86,0.25);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
              <i class="fas fa-comments fa-2x text-warning"></i>
            </div>
            <h6 class="text-white font-weight-bold mb-1">{{ __('Direct Case Representation Line') }}</h6>
            <p class="small text-muted mb-0" style="max-width:420px;margin:0 auto;">{{ __('Type your message below to communicate directly with your assigned Attorney & CPA.') }}</p>
          </div>
        @endforelse
      </div>
      <div class="chat-footer-native">
        <form id="clientChatForm" enctype="multipart/form-data" onsubmit="handleSendChat(event)">
          @csrf
          <div id="chatFileBadge" class="mb-2" style="display:none;">
            <span class="badge badge-dark p-2 text-warning border border-secondary font-weight-normal d-inline-flex align-items-center">
              <i class="fas fa-paperclip mr-1"></i><span id="chatFileName"></span>
              <button type="button" class="btn btn-link text-danger p-0 ml-2" onclick="clearSelectedFile()" style="font-size:14px;line-height:1;">&times;</button>
            </span>
          </div>
          <div class="d-flex align-items-center" style="gap:8px;">
            <button type="button" class="btn btn-dark text-warning p-2" onclick="document.getElementById('chatAttachmentInput').click();"
                    style="background:#0f131a;border:1px solid #28303f;border-radius:8px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-paperclip fa-lg"></i>
            </button>
            <input type="file" name="file" id="chatAttachmentInput" class="d-none" onchange="handleFileSelected(event)" accept=".pdf,.docx,.doc,.jpg,.jpeg,.png,.xlsx">
            <input type="text" name="text" id="chatTextInput" class="form-control chat-input-control" placeholder="{{ __('Type your message to counsel here...') }}" autocomplete="off">
            <button type="submit" id="chatSendBtn" class="btn btn-gold d-inline-flex align-items-center" style="height:44px;">
              <i class="fas fa-paper-plane mr-1" id="sendBtnIcon"></i><span class="d-none d-sm-inline">{{ __('Send') }}</span>
            </button>
          </div>
        </form>
      </div>
    @endif

  </div>
</div>
@endsection

@section('page-script')
@php $isNative = !in_array($provider??'chatwoot',['chatwoot','tawkto','tawk']); @endphp
@if($isNative)
<script>
var convSlug = '{{ $conversation->slug??'' }}';
var lastMsgId = {{ ($messages??collect())->last()?($messages->last()->id??0):0 }};
function scrollDown(){ var e=document.getElementById('chatMessagesStream'); if(e) e.scrollTop=e.scrollHeight; }
function handleFileSelected(e){ var f=e.target.files[0]; if(f){ document.getElementById('chatFileName').innerText=f.name; document.getElementById('chatFileBadge').style.display='block'; } }
function clearSelectedFile(){ document.getElementById('chatAttachmentInput').value=''; document.getElementById('chatFileBadge').style.display='none'; }
function handleSendChat(e){
  e.preventDefault(); if(!convSlug) return;
  var ti=document.getElementById('chatTextInput'); var txt=ti.value.trim();
  var fi=document.getElementById('chatAttachmentInput'); var file=fi.files[0];
  if(!txt&&!file) return;
  var fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('text',txt); if(file) fd.append('file',file);
  var btn=document.getElementById('chatSendBtn'); var ico=document.getElementById('sendBtnIcon');
  btn.disabled=true; ico.className='fas fa-spinner fa-spin';
  $.ajax({ url:'/client/conversation/send-chat/'+convSlug, type:'POST', data:fd, processData:false, contentType:false,
    success:function(r){ if(r.success&&r.message){ appendBubble(r.message); ti.value=''; clearSelectedFile(); lastMsgId=r.message.id; } },
    error:function(x){ alert(x.responseJSON?x.responseJSON.error:'Error sending. Please try again.'); },
    complete:function(){ btn.disabled=false; ico.className='fas fa-paper-plane mr-1'; scrollDown(); }
  });
}
function appendBubble(m){
  var ph=document.getElementById('noMessagesPlaceholder'); if(ph) ph.remove();
  var s=document.getElementById('chatMessagesStream'); if(!s) return;
  if(document.getElementById('msg-item-'+m.id)) return;
  var d=document.createElement('div');
  d.className='chat-bubble '+(m.is_sender?'chat-bubble-out':'chat-bubble-in');
  d.id='msg-item-'+m.id; d.setAttribute('data-msg-id',m.id);
  var h='';
  if(!m.is_sender) h+='<div class="font-weight-bold text-warning small mb-1" style="font-size:11px;">'+(m.user_name||'Counsel')+'</div>';
  if(m.text) h+='<div>'+$('<div>').text(m.text).html()+'</div>';
  if(m.file) h+='<div class="chat-attachment-box"><i class="fas fa-file-alt text-warning"></i><a href="'+m.file+'" target="_blank" class="text-white small font-weight-bold text-decoration-none">'+(m.file_name||'Attachment')+'</a></div>';
  h+='<div class="chat-bubble-time"><span>'+m.time+'</span>'+(m.is_sender?'<i class="fas fa-check-double text-warning ml-1" style="font-size:9px;"></i>':'')+'</div>';
  d.innerHTML=h; s.appendChild(d); scrollDown();
}
function poll(){ if(!convSlug) return; $.get('/client/conversation/poll/'+convSlug,{last_id:lastMsgId},function(r){ if(r&&r.messages&&r.messages.length) r.messages.forEach(function(m){ appendBubble(m); if(m.id>lastMsgId) lastMsgId=m.id; }); }); }
document.addEventListener('DOMContentLoaded',function(){ scrollDown(); setInterval(poll,3000); });
</script>
@endif
@endsection

