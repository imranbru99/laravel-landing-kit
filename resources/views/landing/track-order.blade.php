@extends('layouts.landing')

@section('title', 'অর্ডার ট্র্যাকিং | ' . setting('site_name', 'Amar Shop BD'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 md:py-14">
    
    <!-- Title -->
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-black text-slate-900 mb-2">
            আপনার অর্ডার ট্র্যাক করুন
        </h1>
        <p class="text-slate-600 text-sm">
            অর্ডারের বর্তমান অবস্থা ও ডেলিভারি স্ট্যাটাস জানতে নিচে তথ্য দিন।
        </p>
    </div>

    <!-- Search Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">
        <form action="{{ route('order.track') }}" method="GET" class="space-y-4">
            <div>
                <label for="order_number" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    অর্ডার নম্বর *
                </label>
                <input type="text" 
                       id="order_number" 
                       name="order_number" 
                       value="{{ $orderNumber }}" 
                       placeholder="যেমন: ORD-202609-XXXXX" 
                       required 
                       class="w-full px-4 py-2.5 font-mono text-base bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none">
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    মোবাইল নম্বর *
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       value="{{ $phoneInput }}" 
                       placeholder="017XXXXXXXX" 
                       required 
                       class="w-full px-4 py-2.5 text-base bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none">
            </div>

            <button type="submit" 
                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-6 rounded-xl shadow transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>স্ট্যাটাস দেখুন</span>
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if($searched)
        @if($order)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden space-y-6 p-6 md:p-8">
                
                <!-- Status Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="text-xs text-slate-500">অর্ডার নম্বর:</div>
                        <div class="font-mono font-bold text-lg text-slate-900">{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold uppercase"
                              style="background-color: #ecfdf5; color: #047857;">
                            ● {{ $order->status->labelBn() }}
                        </span>
                    </div>
                </div>

                <!-- Courier Tracking Link if Available -->
                @if($order->courier_tracking_id || $order->courier_consignment_id)
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <div>
                            <strong class="text-blue-900 block text-sm">🚚 কুরিয়ার ট্র্যাকিং কোড: {{ $order->courier_tracking_id ?? $order->courier_consignment_id }}</strong>
                            <span class="text-blue-700">কুরিয়ার পার্টনার: {{ ucfirst($order->courier_driver) }}</span>
                        </div>
                        @if($order->courier_driver === 'steadfast' && $order->courier_tracking_id)
                            <a href="https://steadfast.com.bd/t/{{ $order->courier_tracking_id }}" target="_blank" class="bg-blue-600 text-white font-semibold px-3 py-1.5 rounded-lg shadow-sm hover:bg-blue-700 transition">
                                লাইভ কুরিয়ার ট্র্যাক
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Status Timeline -->
                <div>
                    <h3 class="font-bold text-sm text-slate-800 mb-4">অর্ডার প্রগ্রেস টাইমলাইন:</h3>
                    
                    <div class="relative border-l-2 border-brand-500 ml-3 space-y-6 pb-2">
                        @forelse($order->statusHistories as $history)
                            <div class="relative pl-6">
                                <div class="absolute -left-1.5 top-1 w-3 h-3 bg-brand-600 rounded-full ring-4 ring-white"></div>
                                <div class="text-sm font-bold text-slate-800">
                                    {{ $history->to_status?->labelBn() ?? $history->to_status }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $history->created_at->format('d/m/Y h:i A') }}
                                </div>
                                @if($history->notes)
                                    <div class="text-xs text-slate-600 bg-slate-50 p-2 rounded mt-1">
                                        {{ $history->notes }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="relative pl-6">
                                <div class="absolute -left-1.5 top-1 w-3 h-3 bg-brand-600 rounded-full ring-4 ring-white"></div>
                                <div class="text-sm font-bold text-slate-800">অর্ডার গ্রহণ করা হয়েছে</div>
                                <div class="text-xs text-slate-500">{{ $order->created_at->format('d/m/Y h:i A') }}</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Order Item Summary -->
                <div class="border-t border-slate-100 pt-4 text-xs space-y-2">
                    <h4 class="font-bold text-slate-700">পণ্যের তালিকা:</h4>
                    @foreach($order->items as $item)
                        <div class="flex justify-between py-1 border-b border-slate-50">
                            <span>{{ $item->product_name }} ({{ $item->quantity }}টি)</span>
                            <span class="font-semibold text-slate-900">৳{{ number_format((float) $item->total_price, 0) }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between pt-1 font-bold text-sm text-slate-900">
                        <span>সর্বমোট:</span>
                        <span class="text-brand-600">৳{{ number_format((float) $order->total_amount, 0) }}</span>
                    </div>
                </div>

            </div>
        @else
            <div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-2xl text-center">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="font-bold text-base mb-1">অর্ডার খুঁজে পাওয়া যায়নি!</h3>
                <p class="text-xs text-red-600">
                    আপনার প্রদত্ত অর্ডার নম্বর <strong>{{ $orderNumber }}</strong> এবং মোবাইল নম্বর <strong>{{ $phoneInput }}</strong> এর সাথে কোনো অর্ডারের মিল পাওয়া যায়নি। দয়া করে সঠিক তথ্য দিয়ে পুনরায় চেষ্টা করুন।
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
