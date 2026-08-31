@php
    $chatSettings = \App\Services\ChatwootService::getSettings();
    $chatUser = \App\Services\ChatwootService::getUserIdentity(Auth::user());
@endphp

@if($chatSettings['provider'] === 'chatwoot' && !empty($chatSettings['website_token']) && $chatSettings['website_token'] !== 'YOUR_CHATWOOT_WEBSITE_TOKEN')
<script>
(function(d,t) {
    var BASE_URL = "{{ $chatSettings['base_url'] }}";
    var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
    g.src = BASE_URL + "/packs/js/sdk.js";
    g.async = true;
    s.parentNode.insertBefore(g, s);
    g.onload = function() {
        window.chatwootSDK.run({
            websiteToken: "{{ $chatSettings['website_token'] }}",
            baseUrl: BASE_URL
        });
        @if($chatUser)
        window.addEventListener("chatwoot:ready", function () {
            window.$chatwoot.setUser("{{ $chatUser['identifier'] }}", {
                name: "{{ addslashes($chatUser['name']) }}",
                email: "{{ addslashes($chatUser['email']) }}",
                avatar_url: "{{ $chatUser['avatar_url'] }}",
                identifier_hash: "{{ $chatUser['identifier_hash'] }}",
                phone_number: "{{ $chatUser['phone_number'] }}",
                custom_attributes: {
                    client_id: "{{ $chatUser['custom_attributes']['client_id'] }}",
                    portal_role: "{{ $chatUser['custom_attributes']['portal_role'] }}",
                    account_type: "{{ $chatUser['custom_attributes']['account_type'] }}"
                }
            });
        });
        @endif
    }
})(document, "script");
</script>
@elseif($chatSettings['provider'] === 'tawkto' && !empty($chatSettings['tawkto_property_id']))
@php
    $tawkProp = $chatSettings['tawkto_property_id'];
    $cleanTawk = preg_replace('/.*embed\.tawk\.to\//', '', trim($tawkProp, " \t\n\r;'\"/"));
@endphp
@if(preg_match('/^[a-zA-Z0-9_\/\-]{10,}$/', $cleanTawk))
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/{{ $cleanTawk }}';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
})();
</script>
@endif
@endif
