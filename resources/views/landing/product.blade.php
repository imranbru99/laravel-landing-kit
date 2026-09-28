@extends('layouts.landing')

@section('title', (string) (($product->seo_title ?: $product->name) . ' | ' . setting('site_name', 'Amar Shop BD')))
@section('meta_description', (string) ($product->seo_description ?: ($product->short_description ?: setting('seo_meta_description', ''))))

@section('meta_tags')
<script type="application/ld+json">
{
  "{{ '@context' }}": "https://schema.org/",
  "{{ '@type' }}": "Product",
  "name": "{{ addslashes($product->name) }}",
  "image": "{{ $product->primary_image_url }}",
  "description": "{{ addslashes($product->short_description ?: $product->name) }}",
  "sku": "{{ $product->sku ?: 'LLK-' . $product->id }}",
  "offers": {
    "{{ '@type' }}": "Offer",
    "url": "{{ url('/' . $product->slug) }}",
    "priceCurrency": "BDT",
    "price": "{{ (float) $product->effective_price }}",
    "availability": "{{ $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
}
</script>
@endsection

@section('content')
@if($product->landingPage && $product->landingPage->status === 'published' && $product->landingPage->sections->isNotEmpty())
    <div class="landing-page-dynamic-wrapper">
        @foreach($product->landingPage->sections as $pageSection)
            {!! app(\App\Services\SectionRegistry::class)->render(
                $pageSection->section_type,
                $pageSection->content ?? [],
                $pageSection->style ?? [],
                [
                    'product' => $product,
                    'landingPage' => $product->landingPage,
                    'pageSection' => $pageSection,
                    'districts' => $districts,
                    'deliveryZones' => $deliveryZones,
                ]
            ) !!}
        @endforeach
    </div>
@else
<div class="max-w-5xl mx-auto px-4 py-6 md:py-10" x-data="checkoutApp()">
    
    <!-- Hero / Product Showcase -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8 items-center">
            
            <!-- Gallery / Media -->
            <div class="space-y-4">
                <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden relative flex items-center justify-center border border-slate-200 shadow-inner">
                    @if($product->images->isNotEmpty())
                        <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-6 text-slate-400">
                            <svg class="w-20 h-20 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">{{ $product->name }}</span>
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 bg-red-600 text-white font-bold text-xs uppercase px-2.5 py-1 rounded-full shadow-sm">
                        ধামাকা অফার
                    </div>
                </div>

                <!-- Trust Points -->
                <div class="grid grid-cols-3 gap-2 text-center text-xs text-slate-600 pt-2">
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                        <span class="block text-brand-600 font-bold text-sm">🚚 হোম ডেলিভারি</span>
                        <span>সারা বাংলাদেশে</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                        <span class="block text-brand-600 font-bold text-sm">💵 ক্যাশ অন ডেলিভারি</span>
                        <span>পণ্য পেয়ে টাকা দিন</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                        <span class="block text-brand-600 font-bold text-sm">🛡️ ১০০% অরিজিনাল</span>
                        <span>কোয়ালিটি গ্যারান্টি</span>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="flex flex-col justify-between">
                <div>
                    <div class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold px-2.5 py-1 rounded mb-2">
                        স্টকে আছে - দ্রুত ডেলিভারি
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight mb-3">
                        {{ $product->name }}
                    </h1>

                    @if($product->short_description)
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-4">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- Price Card -->
                    <div class="bg-brand-50/60 p-4 rounded-xl border border-brand-100 mb-6 flex items-baseline gap-3">
                        <span class="text-3xl md:text-4xl font-black text-brand-700">
                            ৳{{ number_format($product->effective_price, 0) }}
                        </span>

                        @if($product->regular_price > $product->effective_price)
                            <span class="text-lg md:text-xl text-slate-400 line-through">
                                ৳{{ number_format((float) $product->regular_price, 0) }}
                            </span>
                            <span class="bg-red-500 text-white font-bold text-xs px-2 py-0.5 rounded">
                                ছাড় ৳{{ number_format((float) ($product->regular_price - $product->effective_price), 0) }}
                            </span>
                        @endif
                    </div>

                    <!-- Offers / Bundle Badges -->
                    @if($product->offers->isNotEmpty())
                        <div class="mb-6 space-y-2">
                            <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">স্পেশাল প্যাকেজ অফার:</div>
                            @foreach($product->offers as $offer)
                                <div class="text-xs bg-amber-50 border border-amber-200 text-amber-900 p-2.5 rounded-lg flex items-center justify-between">
                                    <span>🎁 {{ $offer->title }} (কমপক্ষে {{ $offer->min_quantity }}টি অর্ডার করলে)</span>
                                    <strong class="font-bold text-amber-700">৳{{ number_format((float) $offer->discount_amount, 0) }} ছাড়!</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Scroll to checkout CTA button -->
                <a href="#checkout-form" class="w-full bg-brand-600 hover:bg-brand-700 text-white text-center font-bold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-brand-500/25 transition duration-200 flex items-center justify-center gap-2 text-base md:text-lg">
                    <span>🛒 এখনই অর্ডার করুন</span>
                    <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>

        </div>

        @if($product->long_description)
            <!-- Long Description / Features Tab -->
            <div class="border-t border-slate-100 p-6 md:p-8 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 mb-3">পণ্যের বিস্তারিত বিবরণ:</h3>
                <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed">
                    {!! nl2br(e($product->long_description)) !!}
                </div>
            </div>
        @endif
    </div>

    <!-- One-Product Phone-First Checkout Form -->
    <div id="checkout-form" class="bg-white rounded-2xl shadow-md border-2 border-brand-500/30 overflow-hidden scroll-mt-16">
        
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-brand-700 to-brand-600 p-5 md:p-6 text-white text-center">
            <h2 class="text-xl md:text-2xl font-black mb-1">
                অর্ডার করতে নিচের ফর্মটি পূরণ করুন
            </h2>
            <p class="text-brand-100 text-xs md:text-sm">
                ফোনে কোনো অগ্রিম টাকা লাগবে না, পণ্য হাতে পেয়ে দেখে মূল্য পরিশোধ করবেন।
            </p>
        </div>

        <form action="{{ route('checkout.order') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <!-- Honeypot protection -->
            <input type="text" name="_hp_name" class="hidden" tabindex="-1" autocomplete="off">
            <input type="hidden" name="_hp_time" value="{{ time() }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
            <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
            <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
            <input type="hidden" name="fbclid" value="{{ request('fbclid') }}">
            <input type="hidden" name="gclid" value="{{ request('gclid') }}">

            <!-- 1. Phone-Number-First Field -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label for="phone" class="block font-bold text-slate-800 text-sm mb-1.5 flex items-center justify-between">
                    <span>১. আপনার মোবাইল নম্বর দিন *</span>
                    <span class="text-xs text-brand-600 font-normal">১১ ডিজিটের নম্বর</span>
                </label>
                <div class="relative">
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           x-model="phone" 
                           @input.debounce.400ms="handlePhoneChange()"
                           placeholder="017XXXXXXXX" 
                           required 
                           class="w-full text-lg font-semibold tracking-wider px-4 py-3 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                    
                    <div class="absolute right-3 top-3.5" x-show="isLookingUp" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>

                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Returning Customer Detected Banner -->
                <div x-show="returningCustomer" x-cloak class="mt-3 bg-brand-50 border border-brand-200 text-brand-900 px-3.5 py-2.5 rounded-lg text-xs md:text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-brand-600 text-base">👋</span>
                        <span>স্বাগতম! <strong><span x-text="customerName"></span></strong>, আপনার পূর্বের তথ্য অটো-ফিল করা হয়েছে।</span>
                    </div>
                    <button type="button" @click="showEditFields = !showEditFields" class="text-xs text-brand-700 underline font-semibold">
                        <span x-text="showEditFields ? 'লুকান' : 'পরিবর্তন করুন'"></span>
                    </button>
                </div>
            </div>

            <!-- 2. Customer Name & Address Fields -->
            <div class="space-y-4" x-show="!returningCustomer || showEditFields">
                <div>
                    <label for="name" class="block font-bold text-slate-800 text-sm mb-1.5">২. আপনার নাম লিখুন *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           x-model="name"
                           placeholder="সম্পূর্ণ নাম" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="block font-bold text-slate-800 text-sm mb-1.5">৩. পূর্ণাঙ্গ ঠিকানা লিখুন *</label>
                    <textarea id="address" 
                              name="address" 
                              x-model="address"
                              rows="2" 
                              placeholder="বাড়ি/ফ্ল্যাট নং, রোড নং, এলাকা..." 
                              required 
                              class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none"></textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Cascading District & Thana -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="district_id" class="block font-bold text-slate-800 text-sm mb-1.5">জেলা *</label>
                        <select id="district_id" 
                                name="district_id" 
                                x-model="districtId" 
                                @change="handleDistrictChange()" 
                                required 
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">জেলা নির্বাচন করুন</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" data-inside="{{ $district->is_inside_dhaka ? 1 : 0 }}">
                                    {{ $district->name_bn }} ({{ $district->name_en }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="thana_id" class="block font-bold text-slate-800 text-sm mb-1.5">থানা / উপজেলা</label>
                        <select id="thana_id" 
                                name="thana_id" 
                                x-model="thanaId" 
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="">থানা নির্বাচন করুন</option>
                            <template x-for="thana in thanas" :key="thana.id">
                                <option :value="thana.id" x-text="thana.name_bn + ' (' + thana.name_en + ')'"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 3. Variant Selection (if product has variants) -->
            @if($product->variants->isNotEmpty())
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-800 text-sm mb-2">৪. ভ্যারিয়েন্ট / সাইজ নির্বাচন করুন:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($product->variants as $variant)
                            <label class="border p-3 rounded-lg flex items-center justify-between cursor-pointer transition hover:border-brand-500"
                                   :class="selectedVariant == {{ $variant->id }} ? 'bg-brand-50 border-brand-500 ring-1 ring-brand-500' : 'bg-white border-slate-200'">
                                <div class="flex items-center gap-2">
                                    <input type="radio" 
                                           name="product_variant_id" 
                                           value="{{ $variant->id }}" 
                                           x-model="selectedVariant" 
                                           @change="variantPrice = {{ (float) ($variant->price ?: $product->effective_price) }}; updateSummary()"
                                           class="text-brand-600 focus:ring-brand-500">
                                    <span class="text-xs font-semibold text-slate-800">{{ $variant->attribute_value }}</span>
                                </div>
                                <span class="text-xs font-bold text-brand-700">৳{{ number_format((float) ($variant->price ?: $product->effective_price), 0) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 4. Quantity & Delivery Zone Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Quantity Selector -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-800 text-sm mb-2">পরিমাণ (Quantity):</label>
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                @click="if(quantity > 1) { quantity--; updateSummary(); }" 
                                class="w-10 h-10 bg-white border border-slate-300 hover:bg-slate-100 rounded-lg text-lg font-bold text-slate-700 flex items-center justify-center transition">
                            -
                        </button>
                        <input type="number" 
                               name="quantity" 
                               x-model="quantity" 
                               @input="updateSummary()" 
                               min="1" 
                               max="100" 
                               class="w-16 text-center font-bold text-lg py-1.5 bg-white border border-slate-300 rounded-lg outline-none">
                        <button type="button" 
                                @click="quantity++; updateSummary();" 
                                class="w-10 h-10 bg-white border border-slate-300 hover:bg-slate-100 rounded-lg text-lg font-bold text-slate-700 flex items-center justify-center transition">
                            +
                        </button>
                    </div>
                </div>

                <!-- Delivery Zone Selection -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-800 text-sm mb-2">ডেলিভারি এলাকা:</label>
                    <div class="space-y-2">
                        @foreach($deliveryZones as $zone)
                            <label class="flex items-center justify-between p-2.5 bg-white rounded-lg border cursor-pointer hover:border-brand-500 transition text-xs"
                                   :class="selectedZone == {{ $zone->id }} ? 'border-brand-500 bg-brand-50/50' : 'border-slate-200'">
                                <div class="flex items-center gap-2">
                                    <input type="radio" 
                                           name="delivery_zone_id" 
                                           value="{{ $zone->id }}" 
                                           x-model="selectedZone" 
                                           @change="deliveryFee = {{ (float) $zone->charge }}; updateSummary()"
                                           class="text-brand-600 focus:ring-brand-500">
                                    <span class="font-semibold text-slate-800">{{ $zone->name_bn }}</span>
                                </div>
                                <span class="font-bold text-brand-700">৳{{ number_format((float) $zone->charge, 0) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 5. Payment Method -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block font-bold text-slate-800 text-sm mb-2">পেমেন্ট মেথড:</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="p-3 bg-white border rounded-xl flex items-center gap-3 cursor-pointer hover:border-brand-500 transition"
                           :class="paymentMethod === 'cod' ? 'border-brand-500 bg-brand-50/40 ring-1 ring-brand-500' : 'border-slate-200'">
                        <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-brand-600">
                        <div>
                            <span class="font-bold text-sm block text-slate-800">ক্যাশ অন ডেলিভারি</span>
                            <span class="text-xs text-slate-500">পণ্য হাতে পেয়ে টাকা পরিশোধ</span>
                        </div>
                    </label>

                    @if(setting('bkash_enabled'))
                    <label class="p-3 bg-white border rounded-xl flex items-center gap-3 cursor-pointer hover:border-brand-500 transition"
                           :class="paymentMethod === 'bkash' ? 'border-brand-500 bg-brand-50/40 ring-1 ring-brand-500' : 'border-slate-200'">
                        <input type="radio" name="payment_method" value="bkash" x-model="paymentMethod" class="text-brand-600">
                        <div>
                            <span class="font-bold text-sm block text-slate-800">বিকাশ (bKash)</span>
                            <span class="text-xs text-slate-500">ম্যানুয়াল সেন্ড মানি</span>
                        </div>
                    </label>
                    @endif
                </div>

                <!-- bKash instructions panel -->
                <div x-show="paymentMethod === 'bkash'" x-cloak class="mt-3 p-3 bg-pink-50 border border-pink-200 rounded-lg text-xs space-y-2">
                    <p class="font-semibold text-pink-900">
                        বিকাশ পার্সোনাল নম্বর: <strong>{{ setting('bkash_number', '01700000000') }}</strong>-এ 'Send Money' করুন।
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="payment_sender_number" placeholder="প্রেরক বিকাশ নম্বর" class="px-2 py-1.5 bg-white border rounded text-xs">
                        <input type="text" name="payment_trx_id" placeholder="Transaction ID (TrxID)" class="px-2 py-1.5 bg-white border rounded text-xs">
                    </div>
                </div>
            </div>

            <!-- Optional Customer Note -->
            <div>
                <label for="customer_notes" class="block text-xs font-semibold text-slate-600 mb-1">অর্ডার নোট বা বিশেষ নির্দেশনা (ঐচ্ছিক):</label>
                <input type="text" id="customer_notes" name="customer_notes" placeholder="যেমন: ডেলিভারির সময় কল করবেন..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
            </div>

            <!-- Live Order Summary Card -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-inner space-y-2.5">
                <h4 class="font-bold text-sm text-slate-300 border-b border-slate-800 pb-2">অর্ডার সামারি</h4>
                
                <div class="flex justify-between text-xs text-slate-300">
                    <span>পণ্যের মূল্য (<span x-text="quantity"></span>টি × ৳<span x-text="variantPrice"></span>)</span>
                    <span class="font-semibold text-white">৳<span x-text="subtotal"></span></span>
                </div>

                <div class="flex justify-between text-xs text-amber-400" x-show="discount > 0">
                    <span>স্পেশাল অফার ছাড়</span>
                    <span class="font-semibold">- ৳<span x-text="discount"></span></span>
                </div>

                <div class="flex justify-between text-xs text-slate-300">
                    <span>ডেলিভারি চার্জ</span>
                    <span class="font-semibold text-white">৳<span x-text="deliveryFee"></span></span>
                </div>

                <div class="border-t border-slate-800 pt-2 flex justify-between items-baseline">
                    <span class="text-base font-extrabold text-white">সর্বমোট প্রদেয়:</span>
                    <span class="text-2xl font-black text-brand-400">৳<span x-text="grandTotal"></span></span>
                </div>
            </div>

            <!-- Order Submit Button -->
            <button type="submit" 
                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-black text-lg md:text-xl py-4 px-6 rounded-2xl shadow-xl hover:shadow-brand-500/30 transform hover:-translate-y-0.5 active:translate-y-0 transition duration-150 flex items-center justify-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>অর্ডার নিশ্চিত করুন (৳<span x-text="grandTotal"></span>)</span>
            </button>
            <p class="text-center text-xs text-slate-500">
                🔒 আপনার সকল তথ্য সম্পূর্ণ নিরাপদ এবং গোপন থাকবে।
            </p>
        </form>

    </div>

    <script>
function checkoutApp() {
    return {
        phone: '',
        name: '',
        address: '',
        districtId: '{{ $districts->first()?->id ?? 1 }}',
        thanaId: '',
        thanas: [],
        returningCustomer: false,
        customerName: '',
        showEditFields: false,
        isLookingUp: false,
        selectedVariant: '{{ $product->variants->first()?->id ?? '' }}',
        variantPrice: {{ (float) $product->effective_price }},
        quantity: 1,
        selectedZone: '{{ $deliveryZones->first()?->id ?? 1 }}',
        deliveryFee: {{ (float) ($deliveryZones->first()?->charge ?? 60.0) }},
        paymentMethod: 'cod',
        subtotal: {{ (float) $product->effective_price }},
        discount: 0,
        grandTotal: {{ (float) ($product->effective_price + 60.0) }},

        init() {
            this.handleDistrictChange();
            this.updateSummary();
        },

        handlePhoneChange() {
            if (this.phone.length < 11) {
                this.returningCustomer = false;
                return;
            }

            this.isLookingUp = true;

            // AJAX lookup
            fetch(`/api/checkout/customer-lookup?phone=${encodeURIComponent(this.phone)}`)
                .then(res => res.json())
                .then(data => {
                    this.isLookingUp = false;
                    if (data.valid && data.found) {
                        this.returningCustomer = true;
                        this.customerName = data.name;
                        this.name = data.name;
                        this.address = data.default_address || '';
                        if (data.district_id) {
                            this.districtId = data.district_id;
                            this.thanas = data.thanas || [];
                            this.thanaId = data.thana_id || '';
                        }
                    } else {
                        this.returningCustomer = false;
                    }
                })
                .catch(() => { this.isLookingUp = false; });

            // Capture incomplete order lead
            fetch('/api/checkout/capture-lead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    phone: this.phone,
                    product_id: {{ $product->id }},
                    quantity: this.quantity,
                    district_id: this.districtId,
                    thana_id: this.thanaId,
                    name: this.name,
                    address: this.address,
                })
            }).catch(() => {});
        },

        handleDistrictChange() {
            if (!this.districtId) return;

            fetch(`/api/locations/thanas?district_id=${this.districtId}`)
                .then(res => res.json())
                .then(data => {
                    this.thanas = data;
                });
            
            // Adjust delivery fee if district is Dhaka
            const selectEl = document.getElementById('district_id');
            const selectedOpt = selectEl ? selectEl.options[selectEl.selectedIndex] : null;
            const zoneId = selectedOpt ? selectedOpt.getAttribute('data-zone') : null;
            if (zoneId) {
                this.selectedZone = zoneId;
            }
            this.updateSummary();
        },

        updateSummary() {
            this.subtotal = Math.round(this.variantPrice * this.quantity);
            
            // Volume offers calculation (e.g. buy 2 get 100 off)
            this.discount = 0;
            @foreach($product->offers as $offer)
                if (this.quantity >= {{ $offer->min_quantity }}) {
                    this.discount = {{ (float) $offer->discount_amount }};
                }
            @endforeach

            this.grandTotal = Math.max(0, this.subtotal - this.discount + this.deliveryFee);
        }
    }
}
</script>
</div>
@endif
@endsection
