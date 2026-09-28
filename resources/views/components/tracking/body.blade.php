@php
    $gtmEnabled = (bool) setting('gtm_enabled', false);
    $gtmId = (string) setting('gtm_id', '');
    $gtmCustomDomain = (string) setting('gtm_custom_domain', '');

    $metaPixelEnabled = (bool) setting('meta_pixel_enabled', false);
    $metaPixelId = (string) setting('meta_pixel_id', '');

    $customBody = (string) setting('body_scripts', '');
@endphp

<!-- Google Tag Manager (noscript) -->
@if($gtmEnabled && !empty($gtmId))
@php
    $gtmHost = !empty($gtmCustomDomain) ? rtrim($gtmCustomDomain, '/') : 'https://www.googletagmanager.com';
@endphp
<noscript><iframe src="{{ $gtmHost }}/ns.html?id={{ $gtmId }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

<!-- Meta Pixel (noscript) -->
@if($metaPixelEnabled && !empty($metaPixelId))
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"
/></noscript>
@endif

{!! $customBody !!}
