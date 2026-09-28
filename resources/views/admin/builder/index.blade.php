<!DOCTYPE html>
<html lang="bn" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Builder: {{ $landingPage->title ?? $landingPage->product?->name }} | Amar Shop BD</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js + SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full flex flex-col overflow-hidden" x-data="landingBuilder()" x-init="initBuilder()">

    <!-- Top Navigation Bar -->
    <header class="h-14 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-4 z-30 select-none">
        <!-- Left: Brand & Page Title -->
        <div class="flex items-center space-x-3">
            <a href="/admin/products" class="inline-flex items-center text-xs font-semibold text-slate-400 hover:text-white transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                অ্যাডমিন প্যানেল
            </a>
            <span class="text-slate-600">|</span>
            <div class="flex items-center space-x-2">
                <span class="text-xs bg-emerald-500/20 text-emerald-400 font-bold px-2 py-0.5 rounded border border-emerald-500/30">VISUAL BUILDER</span>
                <h1 class="text-sm font-bold text-white truncate max-w-xs sm:max-w-sm">
                    {{ $landingPage->product?->name ?? $landingPage->title }}
                </h1>
            </div>
        </div>

        <!-- Center: Device Switcher & Canvas Controls -->
        <div class="flex items-center space-x-2 bg-slate-900/60 p-1 rounded-xl border border-slate-700">
            <!-- Desktop -->
            <button type="button" 
                    @click="viewport = 'desktop'" 
                    :class="viewport === 'desktop' ? 'bg-slate-700 text-emerald-400 shadow-sm' : 'text-slate-400 hover:text-white'"
                    class="p-1.5 rounded-lg text-xs font-medium flex items-center transition" title="Desktop View">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </button>

            <!-- Tablet -->
            <button type="button" 
                    @click="viewport = 'tablet'" 
                    :class="viewport === 'tablet' ? 'bg-slate-700 text-emerald-400 shadow-sm' : 'text-slate-400 hover:text-white'"
                    class="p-1.5 rounded-lg text-xs font-medium flex items-center transition" title="Tablet View (768px)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </button>

            <!-- Mobile -->
            <button type="button" 
                    @click="viewport = 'mobile'" 
                    :class="viewport === 'mobile' ? 'bg-slate-700 text-emerald-400 shadow-sm' : 'text-slate-400 hover:text-white'"
                    class="p-1.5 rounded-lg text-xs font-medium flex items-center transition" title="Mobile View (375px)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </button>
        </div>

        <!-- Right: Actions & Publishing -->
        <div class="flex items-center space-x-3">
            <!-- Revisions Dropdown -->
            <div class="relative" x-data="{ openRev: false }">
                <button type="button" @click="openRev = !openRev" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-300 bg-slate-700/60 hover:bg-slate-700 border border-slate-600 transition">
                    <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    রিভিশন হিস্ট্রি
                </button>
                <div x-show="openRev" @click.away="openRev = false" x-cloak class="absolute right-0 mt-2 w-64 bg-slate-800 rounded-xl shadow-xl border border-slate-700 py-2 z-50 text-xs">
                    <div class="px-3 py-1 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-700/60 flex justify-between items-center">
                        <span>পূর্ববর্তী সংস্করণসমূহ</span>
                        <button type="button" @click="createRevisionPrompt()" class="text-emerald-400 hover:underline">+ সেভ করুন</button>
                    </div>
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-700/40">
                        @forelse($landingPage->revisions as $rev)
                            <div class="p-2.5 hover:bg-slate-700/50 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-white">{{ $rev->title }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</p>
                                </div>
                                <button type="button" @click="restoreRevision({{ $rev->id }})" class="px-2 py-1 rounded bg-slate-700 hover:bg-emerald-600 text-white font-medium text-[11px] transition">
                                    রিস্টোর
                                </button>
                            </div>
                        @empty
                            <div class="p-3 text-center text-slate-500">কোনো হিস্ট্রি পাওয়া যায়নি</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- External Preview Link -->
            <a href="{{ route('admin.builder.preview', $landingPage) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-300 bg-slate-700/60 hover:bg-slate-700 border border-slate-600 transition" title="Open Full Preview in New Tab">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                প্রিভিউ
            </a>

            <!-- Status & Publish Button -->
            <button type="button" 
                    @click="togglePublish()" 
                    :class="isPublished ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-600 hover:bg-amber-700'"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold text-white shadow-lg transition flex items-center">
                <span class="w-2 h-2 rounded-full bg-white mr-1.5 animate-pulse"></span>
                <span x-text="isPublished ? 'প্রকাশিত (Published)' : 'ড্রাফট (Draft) - পাবলিশ করুন'"></span>
            </button>
        </div>
    </header>

    <!-- Main 3-Pane Workspace -->
    <div class="flex-1 flex overflow-hidden">

        <!-- Left Pane: Sections Library & Settings (320px) -->
        <aside class="w-80 bg-slate-800/90 border-r border-slate-700 flex flex-col z-20">
            <!-- Sidebar Navigation Tabs -->
            <div class="flex border-b border-slate-700 bg-slate-800 text-xs font-semibold">
                <button type="button" @click="activeTab = 'sections'" :class="activeTab === 'sections' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-700/40' : 'text-slate-400 hover:text-white'" class="flex-1 py-3 text-center transition">
                    সেকশন লাইব্রেরি
                </button>
                <button type="button" @click="activeTab = 'theme'" :class="activeTab === 'theme' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-700/40' : 'text-slate-400 hover:text-white'" class="flex-1 py-3 text-center transition">
                    থিম ও ডিজাইন
                </button>
                <button type="button" @click="activeTab = 'saved'" :class="activeTab === 'saved' ? 'text-emerald-400 border-b-2 border-emerald-400 bg-slate-700/40' : 'text-slate-400 hover:text-white'" class="flex-1 py-3 text-center transition">
                    সেভড
                </button>
            </div>

            <!-- Tab 1: Section Library -->
            <div x-show="activeTab === 'sections'" class="flex-1 flex flex-col overflow-hidden p-3" x-cloak>
                <!-- Search bar -->
                <div class="relative mb-3">
                    <input type="text" x-model="searchQuery" placeholder="সেকশন সার্চ করুন..." class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <!-- Category Filters (Scrollable horizontally) -->
                <div class="flex space-x-1.5 overflow-x-auto pb-2 mb-3 scrollbar-none text-[11px]">
                    <button type="button" @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-emerald-500 text-white' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-700'" class="px-2.5 py-1 rounded-full whitespace-nowrap transition font-medium">
                        সব ({{ count($sections) }})
                    </button>
                    @foreach($categories as $catKey => $catLabel)
                        <button type="button" @click="selectedCategory = '{{ $catKey }}'" :class="selectedCategory === '{{ $catKey }}' ? 'bg-emerald-500 text-white' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-700'" class="px-2.5 py-1 rounded-full whitespace-nowrap transition font-medium">
                            {{ $catLabel }}
                        </button>
                    @endforeach
                </div>

                <!-- Section Cards Grid -->
                <div class="flex-1 overflow-y-auto space-y-2 pr-1">
                    @foreach($sections as $sec)
                        <div x-show="matchesFilter('{{ $sec->category() }}', '{{ strtolower($sec->label()) }}', '{{ $sec->key() }}')" 
                             class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700/80 hover:border-emerald-500/80 transition flex items-center justify-between group">
                            <div class="flex items-center space-x-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-emerald-400 flex-shrink-0 group-hover:bg-emerald-500/20 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </div>
                                <div class="truncate">
                                    <h4 class="text-xs font-bold text-white truncate">{{ $sec->label() }}</h4>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $sec->category() }}</span>
                                </div>
                            </div>
                            <button type="button" @click="addSection('{{ $sec->key() }}')" class="px-2.5 py-1 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white rounded-lg text-xs font-bold transition flex items-center space-x-1">
                                <span>+ যোগ</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 2: Page Theme & Design Tokens -->
            <div x-show="activeTab === 'theme'" class="flex-1 overflow-y-auto p-4 space-y-4" x-cloak>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">প্রাইমারি কালার (Primary Color)</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" x-model="themeTokens.primary_color" class="w-8 h-8 rounded border-0 cursor-pointer bg-transparent">
                            <input type="text" x-model="themeTokens.primary_color" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">অ্যাকসেন্ট কালার (Accent Color)</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" x-model="themeTokens.accent_color" class="w-8 h-8 rounded border-0 cursor-pointer bg-transparent">
                            <input type="text" x-model="themeTokens.accent_color" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">বাংলা ফন্ট (Font Family)</label>
                        <select x-model="themeTokens.font_family" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                            <option value="Hind Siliguri">Hind Siliguri (হিন্দ শিলিগুড়ি)</option>
                            <option value="Noto Sans Bengali">Noto Sans Bengali</option>
                            <option value="Inter">Inter (English)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">কাস্টম CSS (Custom CSS)</label>
                        <textarea x-model="customCss" rows="4" placeholder="/* Custom CSS */" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-xs font-mono text-emerald-400"></textarea>
                    </div>

                    <button type="button" @click="savePageSettings()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-lg">
                        থিম সেটিংস সেভ করুন
                    </button>
                </div>
            </div>

            <!-- Tab 3: Saved Sections -->
            <div x-show="activeTab === 'saved'" class="flex-1 overflow-y-auto p-3 space-y-2" x-cloak>
                <template x-for="item in savedSections" :key="item.id">
                    <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-700 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-white" x-text="item.name"></h4>
                            <span class="text-[10px] text-slate-400" x-text="item.section_type"></span>
                        </div>
                        <button type="button" @click="insertSavedSection(item.id)" class="px-2.5 py-1 bg-emerald-600/30 hover:bg-emerald-600 text-emerald-300 hover:text-white rounded-lg text-xs font-bold transition">
                            + যুক্ত করুন
                        </button>
                    </div>
                </template>
                <div x-show="savedSections.length === 0" class="p-6 text-center text-slate-500 text-xs">
                    কোনো সেভড সেকশন নেই। ক্যানভাস থেকে যেকোনো সেকশন সেভ করতে পারেন।
                </div>
            </div>
        </aside>

        <!-- Center Pane: Canvas Preview & Section Order Manager -->
        <main class="flex-1 flex flex-col bg-slate-950 overflow-hidden relative">
            
            <!-- Quick Section Reorder Bar -->
            <div class="h-10 bg-slate-900/90 border-b border-slate-800 flex items-center px-4 overflow-x-auto space-x-2 text-xs">
                <span class="text-slate-400 font-bold text-[11px] flex-shrink-0">পেজের সেকশনসমূহ:</span>
                <div id="section-sortable-list" class="flex space-x-1.5 items-center">
                    <template x-for="(sec, idx) in currentSections" :key="sec.id">
                        <div :data-id="sec.id" 
                             @click="selectSection(sec.id)"
                             :class="selectedSectionId === sec.id ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                             class="px-2.5 py-1 rounded-md cursor-pointer flex items-center space-x-1.5 transition text-xs whitespace-nowrap border border-slate-700">
                            <span class="cursor-grab opacity-60">⋮⋮</span>
                            <span x-text="sec.label || sec.section_type"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Canvas Viewport Wrapper -->
            <div class="flex-1 flex items-center justify-center p-4 overflow-y-auto bg-slate-950/80">
                <div :class="{
                        'w-full max-w-full h-full': viewport === 'desktop',
                        'w-[768px] max-w-full h-full shadow-2xl': viewport === 'tablet',
                        'w-[375px] max-w-full h-full shadow-2xl': viewport === 'mobile'
                     }" 
                     class="transition-all duration-300 rounded-xl overflow-hidden border border-slate-800 bg-white">
                    <iframe id="canvas-iframe" 
                            src="{{ route('admin.builder.canvas', $landingPage) }}" 
                            class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </main>

        <!-- Right Pane: Section Inspector & Settings (340px) -->
        <aside class="w-84 bg-slate-800/95 border-l border-slate-700 flex flex-col z-20" x-show="selectedSection" x-cloak>
            <!-- Inspector Header -->
            <div class="p-3.5 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider">সেকশন সেটিংস</span>
                    <h3 class="text-xs font-bold text-white" x-text="selectedSection ? selectedSection.label : ''"></h3>
                </div>
                <button type="button" @click="selectedSectionId = null; selectedSection = null" class="text-slate-400 hover:text-white text-sm">✕</button>
            </div>

            <!-- Inspector Form Controls -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" x-show="selectedSection">
                <!-- Title Field -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">শিরোনাম (Title)</label>
                    <input type="text" x-model="selectedSection.content.title" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white">
                </div>

                <!-- Subtitle Field -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">উপ-শিরোনাম / বিবরণ (Subtitle)</label>
                    <textarea x-model="selectedSection.content.subtitle" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-xs text-white"></textarea>
                </div>

                <!-- Badge -->
                <div x-show="selectedSection.content.hasOwnProperty('badge')">
                    <label class="block text-xs font-bold text-slate-300 mb-1">ব্যাজ টেক্সট (Badge)</label>
                    <input type="text" x-model="selectedSection.content.badge" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white">
                </div>

                <!-- Button Text & URL -->
                <div x-show="selectedSection.content.hasOwnProperty('button_text')" class="space-y-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">বাটন টেক্সট</label>
                        <input type="text" x-model="selectedSection.content.button_text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">বাটন লিংক</label>
                        <input type="text" x-model="selectedSection.content.button_url" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <!-- Style settings -->
                <div class="pt-3 border-t border-slate-700/80 space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">স্টাইল ও স্পেসিং</h4>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">ব্যাকগ্রাউন্ড কালার</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" x-model="selectedSection.style.bg_color" class="w-7 h-7 rounded border-0 cursor-pointer bg-transparent">
                            <input type="text" x-model="selectedSection.style.bg_color" class="flex-1 bg-slate-900 border border-slate-700 rounded px-2 py-1 text-xs text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">টেক্সট কালার</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" x-model="selectedSection.style.text_color" class="w-7 h-7 rounded border-0 cursor-pointer bg-transparent">
                            <input type="text" x-model="selectedSection.style.text_color" class="flex-1 bg-slate-900 border border-slate-700 rounded px-2 py-1 text-xs text-white">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-slate-700 space-y-2">
                    <button type="button" @click="saveCurrentSection()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-lg flex items-center justify-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>পরিবর্তন সেভ করুন</span>
                    </button>

                    <button type="button" @click="saveAsReusablePrompt()" class="w-full py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-xl text-xs transition">
                        রিয়্যুজ্যাবল হিসেবে সেভ করুন
                    </button>

                    <button type="button" @click="deleteCurrentSection()" class="w-full py-1.5 bg-red-600/20 hover:bg-red-600/40 text-red-400 font-medium rounded-xl text-xs transition">
                        সেকশন ডিলিট করুন
                    </button>
                </div>
            </div>
        </aside>

    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function landingBuilder() {
            return {
                viewport: 'desktop',
                activeTab: 'sections',
                searchQuery: '',
                selectedCategory: 'all',
                isPublished: {{ $landingPage->status === 'published' ? 'true' : 'false' }},
                currentSections: @json($currentSections ?? []),
                savedSections: @json($savedSections ?? []),
                selectedSectionId: null,
                selectedSection: null,
                themeTokens: @json($landingPage->theme_tokens ?? ['primary_color' => '#10b981', 'accent_color' => '#f59e0b', 'font_family' => 'Hind Siliguri']),
                customCss: @json($landingPage->custom_css ?? ''),

                initBuilder() {
                    const self = this;
                    
                    // Initialize SortableJS on sections list
                    const el = document.getElementById('section-sortable-list');
                    if (el) {
                        new Sortable(el, {
                            animation: 150,
                            handle: '.cursor-grab',
                            onEnd: function () {
                                const orderedIds = Array.from(el.children).map(child => parseInt(child.getAttribute('data-id')));
                                self.reorderSections(orderedIds);
                            }
                        });
                    }

                    // Listen for postMessage from canvas iframe
                    window.addEventListener('message', function (event) {
                        if (!event.data || !event.data.type) return;

                        if (event.data.type === 'llk:select-section') {
                            self.selectSection(event.data.sectionId);
                        } else if (event.data.type === 'llk:section-action') {
                            if (event.data.action === 'edit') {
                                self.selectSection(event.data.sectionId);
                            } else if (event.data.action === 'duplicate') {
                                self.duplicateSection(event.data.sectionId);
                            } else if (event.data.action === 'delete') {
                                self.deleteSectionDirect(event.data.sectionId);
                            }
                        }
                    });
                },

                matchesFilter(cat, label, key) {
                    const matchesCat = this.selectedCategory === 'all' || this.selectedCategory === cat;
                    const q = this.searchQuery.toLowerCase().trim();
                    const matchesSearch = !q || label.includes(q) || key.includes(q);
                    return matchesCat && matchesSearch;
                },

                selectSection(id) {
                    this.selectedSectionId = id;
                    this.selectedSection = this.currentSections.find(s => s.id === id) || null;

                    // Notify iframe to highlight
                    const iframe = document.getElementById('canvas-iframe');
                    if (iframe && iframe.contentWindow) {
                        iframe.contentWindow.postMessage({
                            type: 'llk:highlight-section',
                            sectionId: id
                        }, '*');
                    }
                },

                async addSection(sectionType) {
                    try {
                        const res = await fetch('{{ route("admin.builder.add-section", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ section_type: sectionType })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.currentSections.push({
                                id: data.section.id,
                                section_type: data.section.section_type,
                                label: data.label,
                                position: data.section.position,
                                is_visible: true,
                                content: data.section.content || {},
                                style: data.section.style || {},
                                responsive: data.section.responsive || {}
                            });
                            this.reloadCanvas();
                            this.selectSection(data.section.id);
                        }
                    } catch (e) {
                        console.error('Error adding section', e);
                    }
                },

                async reorderSections(orderedIds) {
                    try {
                        await fetch('{{ route("admin.builder.reorder-sections", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ order: orderedIds })
                        });
                        this.reloadCanvas();
                    } catch (e) {
                        console.error('Error reordering', e);
                    }
                },

                async saveCurrentSection() {
                    if (!this.selectedSection) return;
                    try {
                        const res = await fetch(`/admin/landing-pages/{{ $landingPage->id }}/builder/sections/${this.selectedSection.id}/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                content: this.selectedSection.content,
                                style: this.selectedSection.style,
                                responsive: this.selectedSection.responsive
                            })
                        });
                        if (res.ok) {
                            this.reloadCanvas();
                        }
                    } catch (e) {
                        console.error('Error saving section', e);
                    }
                },

                async duplicateSection(id) {
                    try {
                        const res = await fetch(`/admin/landing-pages/{{ $landingPage->id }}/builder/sections/${id}/duplicate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error('Error duplicating section', e);
                    }
                },

                async deleteSectionDirect(id) {
                    if (!confirm('আপনি কি নিশ্চিতভাবে এই সেকশনটি ডিলিট করতে চান?')) return;
                    try {
                        await fetch(`/admin/landing-pages/{{ $landingPage->id }}/builder/sections/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        this.currentSections = this.currentSections.filter(s => s.id !== id);
                        if (this.selectedSectionId === id) {
                            this.selectedSection = null;
                            this.selectedSectionId = null;
                        }
                        this.reloadCanvas();
                    } catch (e) {
                        console.error('Error deleting section', e);
                    }
                },

                deleteCurrentSection() {
                    if (this.selectedSectionId) {
                        this.deleteSectionDirect(this.selectedSectionId);
                    }
                },

                async togglePublish() {
                    const newStatus = !this.isPublished;
                    try {
                        const res = await fetch('{{ route("admin.builder.publish", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ publish: newStatus })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.isPublished = newStatus;
                        }
                    } catch (e) {
                        console.error('Error toggling publish', e);
                    }
                },

                async savePageSettings() {
                    try {
                        await fetch('{{ route("admin.builder.update-settings", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                theme_tokens: this.themeTokens,
                                custom_css: this.customCss
                            })
                        });
                        this.reloadCanvas();
                    } catch (e) {
                        console.error('Error saving theme settings', e);
                    }
                },

                async saveAsReusablePrompt() {
                    const name = prompt('রিয়্যুজ্যাবল সেকশনের নাম দিন:', this.selectedSection.label);
                    if (!name) return;
                    try {
                        const res = await fetch(`/admin/landing-pages/{{ $landingPage->id }}/builder/sections/${this.selectedSection.id}/save-reusable`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ name: name })
                        });
                        const data = await res.json();
                        if (data.success) {
                            alert('সেকশনটি সফলভাবে সেভ হয়েছে!');
                            this.savedSections.unshift(data.saved_section);
                        }
                    } catch (e) {
                        console.error('Error saving reusable', e);
                    }
                },

                async insertSavedSection(id) {
                    try {
                        const res = await fetch('{{ route("admin.builder.insert-saved-section", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ saved_section_id: id })
                        });
                        const data = await res.json();
                        if (data.success) {
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error('Error inserting saved section', e);
                    }
                },

                async createRevisionPrompt() {
                    const title = prompt('রিভিশনের নাম / নোট দিন:', 'ম্যানুয়াল স্ন্যাপশট ' + new Date().toLocaleTimeString());
                    if (!title) return;
                    try {
                        await fetch('{{ route("admin.builder.create-revision", $landingPage) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ title: title })
                        });
                        window.location.reload();
                    } catch (e) {
                        console.error('Error creating revision', e);
                    }
                },

                async restoreRevision(id) {
                    if (!confirm('আপনি কি পূর্ববর্তী সংস্করণে ফিরে যেতে চান?')) return;
                    try {
                        await fetch(`/admin/landing-pages/{{ $landingPage->id }}/builder/revisions/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        window.location.reload();
                    } catch (e) {
                        console.error('Error restoring revision', e);
                    }
                },

                reloadCanvas() {
                    const iframe = document.getElementById('canvas-iframe');
                    if (iframe && iframe.contentWindow) {
                        iframe.contentWindow.location.reload();
                    }
                }
            }
        }
    </script>
</body>
</html>
