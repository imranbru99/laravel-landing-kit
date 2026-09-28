<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $landingPage->title ?? 'Landing Page Preview' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '{{ $landingPage->theme_tokens["primary_color"] ?? "#10b981" }}',
                            600: '{{ $landingPage->theme_tokens["primary_color"] ?? "#059669" }}',
                            700: '#047857',
                        },
                        accent: {
                            500: '{{ $landingPage->theme_tokens["accent_color"] ?? "#f59e0b" }}',
                        }
                    },
                    fontFamily: {
                        sans: ['"Hind Siliguri"', 'Inter', 'sans-serif'],
                        bangla: ['"Hind Siliguri"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .builder-section-wrapper {
            position: relative;
            transition: all 0.2s ease;
        }
        .builder-section-wrapper:hover {
            outline: 2px dashed #10b981;
            outline-offset: -2px;
        }
        .builder-section-wrapper.active-section {
            outline: 3px solid #10b981;
            outline-offset: -3px;
        }
        .builder-hover-toolbar {
            display: none;
            position: absolute;
            top: 8px;
            right: 12px;
            z-index: 50;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(4px);
            padding: 4px 8px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .builder-section-wrapper:hover .builder-hover-toolbar {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        {!! $landingPage->custom_css ?? '' !!}
    </style>
</head>
<body class="antialiased text-slate-800 selection:bg-brand-500 selection:text-white">

    <div id="llk-canvas-container" class="min-h-screen">
        @forelse($renderedSections as $index => $item)
            <div class="builder-section-wrapper" 
                 id="section-node-{{ $item['id'] }}" 
                 data-section-id="{{ $item['id'] }}"
                 onclick="notifySelect({{ $item['id'] }})">
                
                <!-- Hover Action Toolbar -->
                <div class="builder-hover-toolbar text-white text-xs">
                    <span class="font-mono text-emerald-400 font-bold px-1">{{ $item['label'] }}</span>
                    <button type="button" onclick="event.stopPropagation(); parentAction('edit', {{ $item['id'] }})" class="p-1 hover:text-emerald-400" title="Edit Settings">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button type="button" onclick="event.stopPropagation(); parentAction('duplicate', {{ $item['id'] }})" class="p-1 hover:text-emerald-400" title="Duplicate">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                    <button type="button" onclick="event.stopPropagation(); parentAction('delete', {{ $item['id'] }})" class="p-1 hover:text-red-400" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

                <!-- Section Rendered HTML -->
                <div class="section-content-root">
                    {!! $item['html'] !!}
                </div>
            </div>
        @empty
            <div class="py-24 text-center px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-2xl font-bold">
                    +
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">এই ল্যান্ডিং পেজে এখনো কোনো সেকশন নেই</h3>
                <p class="text-slate-500 max-w-md mx-auto text-sm mb-6">বাম পাশের লাইব্রেরি থেকে যেকোনো সেকশন ক্লিক করে পেজে যুক্ত করুন।</p>
            </div>
        @endforelse
    </div>

    <!-- Communication Bridge to Outer Builder Frame -->
    <script>
        function notifySelect(sectionId) {
            document.querySelectorAll('.builder-section-wrapper').forEach(el => el.classList.remove('active-section'));
            const target = document.getElementById('section-node-' + sectionId);
            if (target) target.classList.add('active-section');

            if (window.parent) {
                window.parent.postMessage({
                    type: 'llk:select-section',
                    sectionId: sectionId
                }, '*');
            }
        }

        function parentAction(action, sectionId) {
            if (window.parent) {
                window.parent.postMessage({
                    type: 'llk:section-action',
                    action: action,
                    sectionId: sectionId
                }, '*');
            }
        }

        // Listen for messages from parent builder
        window.addEventListener('message', function (event) {
            if (!event.data || !event.data.type) return;

            if (event.data.type === 'llk:highlight-section') {
                document.querySelectorAll('.builder-section-wrapper').forEach(el => el.classList.remove('active-section'));
                const el = document.getElementById('section-node-' + event.data.sectionId);
                if (el) {
                    el.classList.add('active-section');
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else if (event.data.type === 'llk:reload-canvas') {
                window.location.reload();
            }
        });
    </script>

    {!! $landingPage->custom_js ?? '' !!}
</body>
</html>
