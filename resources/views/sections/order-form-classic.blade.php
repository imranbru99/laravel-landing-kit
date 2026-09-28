@props([
    'content' => [],
    'style' => [],
    'product' => null,
])

@php
    $title = $content['title'] ?? 'অর্ডার করতে আপনার তথ্য দিন';
    $subtitle = $content['subtitle'] ?? 'ক্যাশ অন ডেলিভারিতে পণ্য পৌঁছাবে আপনার ঠিকানায়।';
    $bgColor = $style['bg_color'] ?? '#ffffff';
    $districts = \App\Models\District::orderBy('name_en')->get();
    $deliveryZones = \App\Models\DeliveryZone::where('is_active', true)->orderBy('charge')->get();
@endphp

<section id="order-form" class="py-12 md:py-16 px-4 sm:px-6 lg:px-8 border-t border-slate-100" style="background-color: {{ $bgColor }};">
    <div class="max-w-4xl mx-auto" x-data="classicOrderForm({{ $product ? $product->id : 0 }}, {{ $product ? $product->effective_price : 0 }})">
        
        <div class="text-center mb-8">
            <span class="inline-block px-3.5 py-1 mb-2 text-xs font-bold uppercase tracking-wider rounded-full bg-emerald-100 text-emerald-800">
                ক্যাশ অন ডেলিভারি
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ $title }}
            </h2>
            <p class="text-sm sm:text-base text-slate-500 mt-2">
                {{ $subtitle }}
            </p>
        </div>

        <form action="{{ route('checkout.order') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-100">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product?->id }}">
            <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
            
            <!-- Honeypot Bot Trap -->
            <div style="display:none !important">
                <input type="text" name="website_url" autocomplete="off" tabindex="-1">
            </div>

            <div class="space-y-6">
                <!-- Phone Field (First!) -->
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        মোবাইল নম্বর <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="tel" 
                               name="customer_phone" 
                               x-model="phone" 
                               @input.debounce.500ms="lookupPhone()" 
                               placeholder="01XXXXXXXXX" 
                               required
                               class="w-full text-base font-semibold px-4 py-3.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <div class="absolute right-3.5 top-3.5" x-show="isLookingUp" x-cloak>
                            <span class="animate-spin text-emerald-600">⌛</span>
                        </div>
                    </div>

                    <!-- Returning Customer Greeting -->
                    <div x-show="returningCustomer" x-cloak class="mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                        <span>স্বাগতম! আপনার সংরক্ষিত ঠিকানা স্বয়ংক্রিয়ভাবে পূরণ করা হয়েছে।</span>
                        <button type="button" @click="editDetails = !editDetails" class="underline font-bold text-emerald-900 ml-2">
                            <span x-text="editDetails ? 'সংরক্ষণ' : 'ঠিকানা পরিবর্তন'"></span>
                        </button>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-1.5">
                            আপনার পুরো নাম <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_name" x-model="name" placeholder="নাম লিখুন" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-1.5">
                            জেলা (District) <span class="text-red-500">*</span>
                        </label>
                        <select name="district_id" x-model="districtId" @change="onDistrictChange()" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">জেলা নির্বাচন করুন</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" data-inside="{{ $d->is_inside_dhaka ? '1' : '0' }}">
                                    {{ $d->name_bn }} ({{ $d->name_en }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        সম্পূর্ণ ডেলিভারি ঠিকানা <span class="text-red-500">*</span>
                    </label>
                    <textarea name="customer_address" x-model="address" rows="2" placeholder="বাড়ি/রোড নং, থানা, এলাকা ইত্যাদি বিস্তারিত লিখুন" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <!-- Quantity & Offers -->
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-2">পরিমাণ (Quantity)</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-lg flex items-center justify-center transition">-</button>
                        <span class="w-12 text-center font-bold text-lg text-slate-800" x-text="quantity"></span>
                        <input type="hidden" name="quantity" :value="quantity">
                        <button type="button" @click="quantity++" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-lg flex items-center justify-center transition">+</button>
                    </div>
                </div>

                <!-- Live Price Summary -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>পণ্যের মূল্য:</span>
                        <span class="font-bold">৳ <span x-text="subtotal"></span></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>ডেলিভারি চার্জ:</span>
                        <span class="font-bold">৳ <span x-text="deliveryCharge"></span></span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                        <span>সর্বমোট মূল্য:</span>
                        <span class="text-emerald-600 text-lg">৳ <span x-text="total"></span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-bold rounded-2xl shadow-xl shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                    <span>অর্ডার কনফার্ম করুন (ক্যাশ অন ডেলিভারি)</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    function classicOrderForm(productId, unitPrice) {
        return {
            productId: productId,
            unitPrice: unitPrice,
            phone: '',
            name: '',
            address: '',
            districtId: '',
            quantity: 1,
            deliveryCharge: 60,
            returningCustomer: false,
            editDetails: false,
            isLookingUp: false,

            get subtotal() {
                return this.quantity * this.unitPrice;
            },
            get total() {
                return this.subtotal + this.deliveryCharge;
            },

            async lookupPhone() {
                if (this.phone.length < 11) return;
                this.isLookingUp = true;
                try {
                    const res = await fetch(`/api/checkout/customer-lookup?phone=${encodeURIComponent(this.phone)}`);
                    const data = await res.json();
                    if (data.found && data.customer) {
                        this.returningCustomer = true;
                        this.name = data.customer.name || this.name;
                        this.address = data.customer.address || this.address;
                        this.districtId = data.customer.district_id || this.districtId;
                        this.onDistrictChange();
                    }
                } catch (e) {
                    console.error('Phone lookup error', e);
                } finally {
                    this.isLookingUp = false;
                }
            },

            onDistrictChange() {
                const select = document.querySelector('select[name="district_id"]');
                if (!select) return;
                const opt = select.options[select.selectedIndex];
                if (opt && opt.getAttribute('data-inside') === '1') {
                    this.deliveryCharge = 60;
                } else {
                    this.deliveryCharge = 120;
                }
            }
        }
    }
</script>
