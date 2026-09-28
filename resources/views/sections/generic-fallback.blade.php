@props([
    'content' => [],
    'style' => [],
    'responsive' => [],
    'sectionType' => null,
])

@php
    $title = $content['title'] ?? ($content['headline'] ?? '');
    $subtitle = $content['subtitle'] ?? ($content['description'] ?? '');
    $badge = $content['badge'] ?? '';
    $bgColor = $style['bg_color'] ?? '#ffffff';
    $textColor = $style['text_color'] ?? '#1e293b';
    $paddingTop = $style['padding_top'] ?? 'py-12';
    $paddingBottom = $style['padding_bottom'] ?? '';
@endphp

<section class="{{ $paddingTop }} {{ $paddingBottom }} px-4 sm:px-6 lg:px-8 transition-all" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
    <div class="max-w-5xl mx-auto text-center">
        @if(!empty($badge))
            <span class="inline-block px-3 py-1 mb-3 text-xs font-semibold tracking-wider uppercase rounded-full bg-emerald-100 text-emerald-800">
                {{ $badge }}
            </span>
        @endif

        @if(!empty($title))
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight mb-4">
                {{ $title }}
            </h2>
        @endif

        @if(!empty($subtitle))
            <p class="text-base sm:text-lg opacity-80 max-w-2xl mx-auto mb-6">
                {{ $subtitle }}
            </p>
        @endif

        @if(!empty($content['items']) && is_array($content['items']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 text-left">
                @foreach($content['items'] as $item)
                    <div class="p-6 rounded-2xl bg-white/60 dark:bg-slate-800/60 shadow-sm border border-slate-100 dark:border-slate-700">
                        @if(!empty($item['icon']))
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                                <span class="font-bold text-lg">{!! $item['icon'] !!}</span>
                            </div>
                        @endif
                        @if(!empty($item['title']))
                            <h4 class="font-bold text-lg mb-2">{{ $item['title'] }}</h4>
                        @endif
                        @if(!empty($item['description']))
                            <p class="text-sm opacity-75">{{ $item['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($content['button_text']))
            <div class="mt-8">
                <a href="{{ $content['button_url'] ?? '#order-form' }}" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5">
                    {{ $content['button_text'] }}
                </a>
            </div>
        @endif
    </div>
</section>
