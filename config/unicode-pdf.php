<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default PDF Engine
    |--------------------------------------------------------------------------
    |
    | The default PDF rendering engine adapter to use.
    | Supported: "native", "dompdf", "mpdf", "tcpdf", "browsershot", "null"
    |
    | "native" is the built-in engine (no extra Composer packages required).
    |
    */
    'engine' => env('UNICODE_PDF_ENGINE', 'native'),

    /*
    |--------------------------------------------------------------------------
    | Default Font & Fallbacks
    |--------------------------------------------------------------------------
    |
    | Define the primary default font family and ordered fallback chain.
    | When rendering multilingual or mixed-script documents, the system will
    | construct font-family stacks or configure the engine to resolve glyphs
    | across these fallback fonts.
    |
    */
    'default_font' => env('UNICODE_PDF_DEFAULT_FONT', 'Noto Sans'),

    'custom_font_path' => env('UNICODE_PDF_CUSTOM_FONTS', 'C:\\Users\\imran\\Documents\\server\\font\\dist'),

    'fallback_fonts' => [
        'Noto Sans',
        'AI-Borno',
        'Noto Sans Bengali',
        'Noto Sans Arabic',
        'Noto Sans Devanagari',
        'Noto Sans Thai',
        'Noto Sans Hebrew',
        'Noto Sans Tamil',
        'Noto Sans Telugu',
        'Noto Sans Malayalam',
        'Noto Sans Gujarati',
        'Noto Sans Gurmukhi',
        'Noto Sans CJK SC',
        'Noto Sans CJK TC',
        'Noto Sans CJK JP',
        'Noto Sans CJK KR',
    ],

    /*
    |--------------------------------------------------------------------------
    | Registered Fonts
    |--------------------------------------------------------------------------
    |
    | Custom registered font families and their variant file paths (TTF/OTF).
    | Example:
    |   'Noto Sans Bengali' => [
    |       'regular'     => storage_path('fonts/NotoSansBengali-Regular.ttf'),
    |       'bold'        => storage_path('fonts/NotoSansBengali-Bold.ttf'),
    |       'italic'      => storage_path('fonts/NotoSansBengali-Regular.ttf'),
    |       'bold_italic' => storage_path('fonts/NotoSansBengali-Bold.ttf'),
    |   ]
    |
    */
    'fonts' => [
        'AI-Borno' => [
            'regular' => 'C:\\Users\\imran\\Documents\\server\\font\\dist\\AI-Borno-Regular.ttf',
            'bold' => 'C:\\Users\\imran\\Documents\\server\\font\\dist\\AI-Borno-Bold.ttf',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Font Storage & Cache Directory
    |--------------------------------------------------------------------------
    |
    | Local directory where custom fonts and parsed font metadata are stored.
    |
    */
    'font_path' => storage_path('app/unicode-pdf/fonts'),
    'font_cache' => storage_path('app/unicode-pdf/cache/fonts'),

    /*
    |--------------------------------------------------------------------------
    | Automatic Font & Script Detection
    |--------------------------------------------------------------------------
    |
    | When enabled, the package analyzes the input HTML/text for Unicode
    | script ranges (e.g. Bengali, Arabic, Devanagari, CJK) and automatically
    | injects appropriate CSS font-family rules or engine font mappings.
    |
    */
    'font_detection' => [
        'enabled' => true,
        'auto_inject_css' => true,
        'dominant_script_only' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Text Direction & Bi-directional Support
    |--------------------------------------------------------------------------
    |
    | Direction can be 'auto', 'ltr', or 'rtl'.
    | 'auto': automatically detects dominant script direction and handles mixed LTR/RTL.
    | 'bidi': enables Unicode BiDi algorithm assistance where required by engines.
    |
    */
    'direction' => 'auto',
    'bidi' => true,

    /*
    |--------------------------------------------------------------------------
    | Unicode Normalization
    |--------------------------------------------------------------------------
    |
    | Normalize Unicode character strings to ensure consistent visual representation
    | and prevent decomposed diacritic anomalies.
    | Forms: "NFC", "NFD", "NFKC", "NFKD"
    |
    */
    'normalization' => [
        'enabled' => false,
        'form' => 'NFC',
    ],

    /*
    |--------------------------------------------------------------------------
    | Paper Size & Margins Defaults
    |--------------------------------------------------------------------------
    |
    | Default paper size: "a4", "letter", "legal", "a3", "a5", etc.
    | Orientation: "portrait" or "landscape"
    | Margins in millimeters (mm) or points (pt)
    |
    */
    'paper' => 'a4',
    'orientation' => 'portrait',
    'margins' => [
        'top' => 10,
        'right' => 10,
        'bottom' => 10,
        'left' => 10,
        'unit' => 'mm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Emoji Support
    |--------------------------------------------------------------------------
    |
    | Configure emoji handling. Many PDF engines have limited support for
    | multi-color OpenType SVG/COLR emoji tables.
    |
    */
    'emoji' => [
        'enabled' => true,
        'fallback_font' => 'Noto Color Emoji',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security & Resource Protection
    |--------------------------------------------------------------------------
    |
    | Restrict remote asset loading to avoid Server-Side Request Forgery (SSRF).
    | Validate local filesystem paths to prevent directory traversal.
    |
    */
    'security' => [
        'allow_remote_images' => false,
        'allow_remote_fonts' => false,
        'allowed_remote_hosts' => [],
        'allowed_local_paths' => [
            base_path(),
            storage_path(),
            public_path(),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance & Caching
    |--------------------------------------------------------------------------
    |
    | Cache parsed font tables, glyph metrics, and detected script profiles.
    |
    */
    'performance' => [
        'cache_enabled' => true,
        'cache_path' => storage_path('app/unicode-pdf/cache'),
        'lazy_font_loading' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging & Diagnostics
    |--------------------------------------------------------------------------
    |
    | Log font resolution, script detection, and rendering metrics.
    |
    */
    'logging' => [
        'enabled' => env('UNICODE_PDF_LOGGING', false),
        'channel' => env('UNICODE_PDF_LOG_CHANNEL', 'stack'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Output Cache
    |--------------------------------------------------------------------------
    |
    | Optional Laravel cache store for rendered PDF binaries. Documents can
    | opt in with ->cache($seconds). Set store to null to use the default.
    |
    */
    'cache' => [
        'store' => env('UNICODE_PDF_CACHE_STORE'),
        'ttl' => (int) env('UNICODE_PDF_CACHE_TTL', 3600),
        'path' => storage_path('app/unicode-pdf/cache'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | Default queue connection and name for GeneratePdfJob when using ->queue().
    |
    */
    'queue' => [
        'connection' => env('UNICODE_PDF_QUEUE_CONNECTION'),
        'queue' => env('UNICODE_PDF_QUEUE', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Named Profiles
    |--------------------------------------------------------------------------
    |
    | Reusable document presets referenced via UnicodePdf::profile('invoice').
    |
    | 'invoice' => [
    |     'engine' => 'mpdf',
    |     'preset' => 'bengali',
    |     'paper' => 'a4',
    |     'orientation' => 'portrait',
    |     'locale' => 'bn',
    | ],
    |
    */
    'profiles' => [
        'invoice' => [
            'preset' => 'universal',
            'paper' => 'a4',
            'orientation' => 'portrait',
        ],
        'receipt' => [
            'preset' => 'bengali',
            'paper' => 'a5',
            'orientation' => 'portrait',
        ],
        'rtl-report' => [
            'preset' => 'arabic',
            'paper' => 'a4',
            'orientation' => 'portrait',
            'direction' => 'rtl',
        ],
    ],

];
