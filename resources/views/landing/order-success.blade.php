@extends('layouts.landing')

@section('title', 'অর্ডার সফল হয়েছে! | ' . setting('site_name', 'Amar Shop BD'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 md:py-14">
    
    <!-- Success Badge & Heading -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-10 text-center mb-6">
        <div class="w-20 h-20 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-brand-50">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <span class="bg-brand-50 text-brand-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            অর্ডার নিশ্চিতকরণ
        </span>

        <h1 class="text-2xl md:text-3xl font-black text-slate-900 mt-3 mb-2">
            ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গৃহীত হয়েছে।
        </h1>
        <p class="text-slate-600 text-sm md:text-base leading-relaxed max-w-md mx-auto">
            আমাদের কাস্টমার কেয়ার প্রতিনিধি শীঘ্রই আপনার সাথে ফোনে যোগাযোগ করে অর্ডার কনফার্ম করবেন।
        </p>

        <!-- Order Number Badge -->
        <div class="mt-6 inline-block bg-slate-900 text-white px-6 py-3 rounded-2xl">
            <span class="text-xs text-slate-400 block uppercase">অর্ডার ট্র্যাকিং নম্বর:</span>
            <strong class="text-xl md:text-2xl font-mono tracking-wider text-brand-400">
                {{ $order->order_number }}
            </strong>
        </div>
    </div>

    <!-- Order Summary Details -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6 space-y-4">
        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>অর্ডারের বিস্তারিত</span>
            <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-full">
                {{ $order->status->labelBn() }}
            </span>
        </h3>

        <!-- Customer Snapshot -->
        <div class="text-xs space-y-1.5 text-slate-600 bg-slate-50 p-4 rounded-xl">
            <div><strong>গ্রাহকের নাম:</strong> {{ $order->customer_name }}</div>
            <div><strong>মোবাইল নম্বর:</strong> {{ $order->customer_phone }}</div>
            <div><strong>ডেলিভারি ঠিকানা:</strong> {{ $order->customer_address }}, {{ $order->district?->name_bn }}</div>
            <div><strong>পেমেন্ট পদ্ধতি:</strong> {{ strtoupper($order->payment_method) }} (ক্যাশ অন ডেলিভারি)</div>
        </div>

        <!-- Ordered Items -->
        <div class="space-y-3 pt-2">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between text-sm py-2 border-b border-slate-100 last:border-none">
                    <div>
                        <div class="font-bold text-slate-800">{{ $item->product_name }}</div>
                        @if($item->variant_name)
                            <div class="text-xs text-slate-500">{{ $item->variant_name }}</div>
                        @endif
                        <div class="text-xs text-slate-400">পরিমাণ: {{ $item->quantity }} টি × ৳{{ number_format((float) $item->unit_price, 0) }}</div>
                    </div>
                    <div class="font-bold text-slate-900">
                        ৳{{ number_format((float) $item->total_price, 0) }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Totals breakdown -->
        <div class="border-t border-slate-100 pt-3 space-y-2 text-xs text-slate-600">
            <div class="flex justify-between">
                <span>সাবটোটাল</span>
                <span class="font-semibold text-slate-900">৳{{ number_format((float) $order->subtotal, 0) }}</span>
            </div>

            @if($order->discount_amount > 0)
                <div class="flex justify-between text-amber-600 font-semibold">
                    <span>ডিসকাউন্ট ছাড়</span>
                    <span>- ৳{{ number_format((float) $order->discount_amount, 0) }}</span>
                </div>
            @endif

            <div class="flex justify-between">
                <span>ডেলিভারি চার্জ</span>
                <span class="font-semibold text-slate-900">৳{{ number_format((float) $order->delivery_charge, 0) }}</span>
            </div>

            <div class="border-t border-slate-200 pt-2 flex justify-between items-baseline text-base font-extrabold text-slate-900">
                <span>সর্বমোট পরিশোধযোগ্য:</span>
                <span class="text-xl text-brand-600 font-black">৳{{ number_format((float) $order->total_amount, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('order.track', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}" 
           class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 px-4 rounded-xl text-center text-sm shadow transition">
            🚚 অর্ডার ট্র্যাক করুন
        </a>
        <a href="/" 
           class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-3.5 px-4 rounded-xl text-center text-sm transition">
            🏠 হোম পেজে ফিরুন
        </a>
    </div>

</div>
@endsection

@push('scripts')
@if($firePurchaseEvent)
{{-- Purchase Event Tracking Script (Executed exactly once, deduplicated) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var purchasePayload = {
        transaction_id: "{{ $order->order_number }}",
        value: {{ (float) $order->total_amount }},
        currency: "BDT",
        shipping: {{ (float) $order->delivery_charge }},
        items: [
            @foreach($order->items as $item)
            {
                item_id: "{{ $item->product_id }}",
                item_name: "{{ addslashes($item->product_name) }}",
                price: {{ (float) $item->unit_price }},
                quantity: {{ (int) $item->quantity }}
            },
            @endforeach
        ]
    };

    // Push to Google Tag Manager dataLayer
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        event: 'purchase',
        ecommerce: purchasePayload
    });

    // Fire Meta Pixel Purchase event with unique event_id for CAPI deduplication
    if (typeof window.fbq === 'function') {
        window.fbq('track', 'Purchase', {
            value: {{ (float) $order->total_amount }},
            currency: 'BDT',
            content_type: 'product',
            content_ids: [{{ $order->items->pluck('product_id')->implode(',') }}]
        }, {
            eventID: 'purchase_{{ $order->id }}'
        });
    }

    console.log('[LLK Tracking] Purchase event fired successfully:', purchasePayload);
});
</script>
@endif
@endpush
