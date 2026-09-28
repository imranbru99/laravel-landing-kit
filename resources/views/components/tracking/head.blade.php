@php
    $gtmEnabled = (bool) setting('gtm_enabled', false);
    $gtmId = (string) setting('gtm_id', '');
    $gtmCustomDomain = (string) setting('gtm_custom_domain', '');

    $metaPixelEnabled = (bool) setting('meta_pixel_enabled', false);
    $metaPixelId = (string) setting('meta_pixel_id', '');

    $ga4Enabled = (bool) setting('ga4_enabled', false);
    $ga4Id = (string) setting('ga4_measurement_id', '');

    $tiktokEnabled = (bool) setting('tiktok_pixel_enabled', false);
    $tiktokId = (string) setting('tiktok_pixel_id', '');

    $clarityId = (string) setting('clarity_id', '');
    $customHead = (string) setting('header_scripts', '');
@endphp

<!-- DataLayer & LLK Unified Tracker Initialization -->
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    // Google Consent Mode v2 default
    gtag('consent', 'default', {
        'ad_storage': 'granted',
        'ad_user_data': 'granted',
        'ad_personalization': 'granted',
        'analytics_storage': 'granted'
    });

    window.LLK = window.LLK || {};
    window.LLK.track = function (event, payload) {
        payload = payload || {};
        const eventId = payload.event_id || ('llk_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9));
        payload.event_id = eventId;

        // Push standard GA4 ecommerce dataLayer
        window.dataLayer.push({
            event: event,
            ecommerce: payload,
            event_id: eventId
        });

        // Trigger Meta Pixel if loaded
        if (typeof window.fbq === 'function') {
            const metaMap = {
                'page_view': 'PageView',
                'view_item': 'ViewContent',
                'add_to_cart': 'AddToCart',
                'begin_checkout': 'InitiateCheckout',
                'generate_lead': 'Lead',
                'purchase': 'Purchase'
            };
            const metaEvent = metaMap[event] || event;
            window.fbq('track', metaEvent, payload, { eventID: eventId });
        }

        // Trigger TikTok Pixel if loaded
        if (typeof window.ttq === 'object') {
            window.ttq.track(event, payload, { event_id: eventId });
        }

        console.log('[LLK Tracking]', event, payload);
    };
</script>

<!-- Google Tag Manager -->
@if($gtmEnabled && !empty($gtmId))
@php
    $gtmHost = !empty($gtmCustomDomain) ? rtrim($gtmCustomDomain, '/') : 'https://www.googletagmanager.com';
@endphp
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'{{ $gtmHost }}/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
@endif

<!-- Meta Pixel Code -->
@if($metaPixelEnabled && !empty($metaPixelId))
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $metaPixelId }}');
fbq('track', 'PageView');
</script>
@endif

<!-- Google Analytics 4 (Direct) -->
@if($ga4Enabled && !empty($ga4Id) && !$gtmEnabled)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $ga4Id }}');
</script>
@endif

<!-- Microsoft Clarity -->
@if(!empty($clarityId))
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "{{ $clarityId }}");
</script>
@endif

{!! $customHead !!}
