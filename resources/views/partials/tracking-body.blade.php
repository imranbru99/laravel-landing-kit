{{-- Laravel Landing Kit Tracking Body scripts --}}
@if(setting('gtm_id'))
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('gtm_id') }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif

{!! setting('custom_body_scripts') !!}
