@props([
    'content' => [],
    'style' => [],
    'product' => null,
])

@php
    $title = $content['title'] ?? '১০০% অরিজিনাল ও প্রিমিয়াম কোয়ালিটি পণ্য';
    $subtitle = $content['subtitle'] ?? 'সারা বাংলাদেশে দ্রুততম হোম ডেলিভারি এবং পণ্য হাতে পেয়ে ক্যাশ অন ডেলিভারিতে মূল্য পরিশোধের সুবিধা।';
    $badge = $content['badge'] ?? 'ধামাকা অফার';
    $ctaText = $content['cta_text'] ?? 'এখনই অর্ডার করুন';
    $ctaLink = $content['cta_link'] ?? '#order-form';
    $imageUrl = $content['image_url'] ?? ($product?->primary_image_url ?? '');
    $bgColor = $style['bg_color'] ?? '#ffffff';
@endphp

<section class="py-12 md:py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-100" style="background-color: {{ $bgColor }};">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <!-- Text Column -->
        <div class="text-left space-y-5">
            @if(!empty($badge))
                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700 animate-pulse">
                    🔥 {{ $badge }}
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                {{ $title }}
            </h1>

            <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                {{ $subtitle }}
            </p>

            @if($product)
                <div class="flex items-baseline space-x-3 pt-2">
                    <span class="text-3xl font-black text-brand-600">
                        {{ format_bdt($product->effective_price) }}
                    </span>
                    @if($product->sale_price && (float)$product->regular_price > (float)$product->sale_price)
                        <span class="text-lg text-slate-400 line-through">
                            {{ format_bdt($product->regular_price) }}
                        </span>
                        <span class="bg-red-500 text-white font-bold text-xs px-2 py-0.5 rounded-full">
                            -{{ $product->discount_percent }}% ছাড়
                        </span>
                    @endif
                </div>
            @endif

            <!-- Trust Points -->
            <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-xs text-slate-600 font-semibold">
                <div class="flex items-center space-x-2">
                    <span class="text-emerald-500 text-lg">🚚</span>
                    <span>সারা দেশে হোম ডেলিভারি</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-emerald-500 text-lg">💵</span>
                    <span>ক্যাশ অন ডেলিভারি</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-emerald-500 text-lg">🛡️</span>
                    <span>মানিব্যাক গ্যারান্টি</span>
                </div>
            </div>

            <div class="pt-4">
                <a href="{{ $ctaLink }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-600/30 transition-all transform hover:-translate-y-1">
                    {{ $ctaText }} →
                </a>
            </div>
        </div>

        <!-- Image Column -->
        <div class="relative">
            <div class="aspect-square rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-slate-100 flex items-center justify-center relative">
                @if(!empty($imageUrl))
                    <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
                @else
                    <div class="p-8 text-center text-slate-400">
                        <svg class="w-24 h-24 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium">প্রোডাক্টের ছবি যুক্ত করুন</span>
                    </div>
                @endif
                <div class="absolute top-4 left-4 bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                    স্টকে আছে
                </div>
            </div>
        </div>
    </div>
</section>
