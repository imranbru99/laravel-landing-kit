<!DOCTYPE html>
<html lang="{{ setting('site_language', 'bn') }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', setting('site_name', 'Laravel Landing Kit'))</title>
    <meta name="description" content="@yield('meta_description', setting('seo_meta_description', 'High performance e-commerce landing page'))">
    @yield('meta_tags')

    <!-- Fonts: Hind Siliguri (Bengali) & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via official modern standalone CDN for lightning-fast zero-build rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        accent: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'Inter', 'sans-serif'],
                        bangla: ['Hind Siliguri', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }
    </style>

    @stack('styles')

    <!-- Tracking Head Injection -->
    @include('partials.tracking-head')
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col selection:bg-brand-500 selection:text-white">
    <!-- Tracking Body Injection -->
    @include('partials.tracking-body')

    <!-- Header / Announcement Bar -->
    <header class="bg-brand-700 text-white py-2.5 px-4 text-center text-xs md:text-sm font-medium shadow-sm sticky top-0 z-40 backdrop-blur-md bg-opacity-95">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="/" class="font-bold text-base md:text-lg tracking-tight flex items-center gap-1.5">
                <span class="bg-white text-brand-700 p-1 rounded font-black text-xs">LLK</span>
                <span>{{ setting('site_name', 'Amar Shop BD') }}</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline text-brand-100">📞 হটলাইন:</span>
                <a href="tel:{{ setting('primary_phone', '01700000000') }}" class="font-bold text-white hover:underline bg-brand-800/60 px-2.5 py-1 rounded-full text-xs md:text-sm">
                    {{ setting('primary_phone', '01700000000') }}
                </a>
                <a href="/track-order" class="text-xs bg-white/10 hover:bg-white/20 px-2 py-1 rounded transition text-white">
                    অর্ডার ট্র্যাক করুন
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-10 px-4 mt-16 border-t border-slate-800 text-sm">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <h3 class="text-white text-lg font-bold mb-2">{{ setting('site_name', 'Amar Shop BD') }}</h3>
                <p class="text-xs leading-relaxed text-slate-400">
                    {{ setting('footer_about', 'সারাদেশে ক্যাশ অন ডেলিভারিতে ১০০% অরিজিনাল ও প্রিমিয়াম প্রোডাক্ট হোম ডেলিভারি। যেকোনো প্রয়োজনে আমাদের হটলাইনে যোগাযোগ করুন।') }}
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">ডেলিভারি ও নিশ্চয়তা</h4>
                <ul class="text-xs space-y-2">
                    <li>✓ পণ্য হাতে পেয়ে দেখে মূল্য পরিশোধ</li>
                    <li>✓ ঢাকা সিটিতে ২৪-৪৮ ঘণ্টার মধ্যে ডেলিভারি</li>
                    <li>✓ সারা বাংলাদেশে ২-৩ দিনের মধ্যে হোম ডেলিভারি</li>
                    <li>✓ ৩ দিনের সহজ রিপ্লেসমেন্ট পলিসি</li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">যোগাযোগ</h4>
                <p class="text-xs">হটলাইন: <strong class="text-white">{{ setting('primary_phone', '01700000000') }}</strong></p>
                <p class="text-xs mt-1">ইমেইল: {{ setting('contact_email', 'support@amarshopbd.com') }}</p>
                <p class="text-xs mt-1">ঠিকানা: {{ setting('address', 'ঢাকা, বাংলাদেশ') }}</p>
            </div>
        </div>
        <div class="max-w-6xl mx-auto border-t border-slate-800 mt-8 pt-6 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ setting('site_name', 'Amar Shop BD') }}. All rights reserved. Powered by Laravel Landing Kit.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
