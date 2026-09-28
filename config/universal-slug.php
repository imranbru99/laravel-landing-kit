<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Slug Separator
    |--------------------------------------------------------------------------
    |
    | The default separator character used to replace spaces, punctuation,
    | and illegal URL characters. Typically a hyphen '-' or underscore '_'.
    |
    */
    'default_separator' => '-',

    /*
    |--------------------------------------------------------------------------
    | Slug Generation Mode
    |--------------------------------------------------------------------------
    |
    | Supported modes:
    | - 'preserve_unicode': (Recommended) Keeps non-Latin scripts (Bengali,
    |   Arabic, Hindi, Chinese, Cyrillic, etc.) native and clean.
    | - 'transliterate': Converts non-Latin characters to closest Latin/ASCII
    |   counterparts if Intl Transliterator is available.
    | - 'ascii_only': Strips out any characters that cannot be converted to ASCII.
    |
    */
    'mode' => 'preserve_unicode',

    /*
    |--------------------------------------------------------------------------
    | Convert to Lowercase
    |--------------------------------------------------------------------------
    |
    | Whether the generated slug should be automatically converted to lowercase
    | using multibyte-safe `mb_strtolower()`.
    |
    */
    'lowercase' => true,

    /*
    |--------------------------------------------------------------------------
    | Maximum Word Limit
    |--------------------------------------------------------------------------
    |
    | Limit the slug to the first N words (e.g. 7 or 10 words).
    | Set to null for unlimited words.
    |
    */
    'word_limit' => null,

    /*
    |--------------------------------------------------------------------------
    | Maximum Character Length
    |--------------------------------------------------------------------------
    |
    | Maximum length (in UTF-8 characters) for generated slugs. Set to null
    | for unlimited length.
    |
    */
    'max_length' => null,

    /*
    |--------------------------------------------------------------------------
    | Random Alphanumeric Suffix
    |--------------------------------------------------------------------------
    |
    | Append a random alphanumeric string (e.g. 2 to 9 characters like 'a8k3x')
    | to the end of the slug (e.g., "my-slug-8xf2k").
    | Set to false or null to disable, or an integer (2-9 or more) for length.
    |
    */
    'random_suffix' => false,
    'random_suffix_length' => 5,

    /*
    |--------------------------------------------------------------------------
    | Strip Stopwords
    |--------------------------------------------------------------------------
    |
    | Automatically strip multilingual filler words when generating slugs.
    |
    */
    'remove_stopwords' => false,

    /*
    |--------------------------------------------------------------------------
    | Default Fallback Slug
    |--------------------------------------------------------------------------
    |
    | When an input string has no usable characters (e.g. pure emojis or symbols),
    | this fallback string will be used instead of returning an empty slug.
    |
    */
    'fallback' => 'default',

    /*
    |--------------------------------------------------------------------------
    | SEO Slug History & 301 Redirect Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for models using HasSlugHistory.
    |
    */
    'history' => [
        'enabled' => true,
        'max_entries_per_model' => 5, // Keep only the latest N slugs per model
        'prune_after_days' => 90,     // Days to retain old slugs before pruning
        'allowed_roles' => ['admin', 'superadmin'], // Roles permitted to view/inspect slug history
        'permission' => null, // Optional custom permission name (e.g. 'view-slug-history')
        'auto_redirect' => true, // 301 historical slugs during route model binding
        'dont_reuse' => true,    // Never recycle an archived slug
    ],

    /*
    |--------------------------------------------------------------------------
    | Reserved Slugs
    |--------------------------------------------------------------------------
    |
    | Route words that should never be assigned to a model slug. Eloquent
    | models treat these as already taken and append a unique suffix.
    |
    */
    'reserved' => [
        'admin', 'administrator', 'superadmin', 'api', 'login', 'logout',
        'register', 'signup', 'signin', 'auth', 'oauth', 'callback',
        'dashboard', 'settings', 'profile', 'account', 'user', 'users',
        'livewire', 'filament', 'horizon', 'telescope', 'nova', 'pulse',
        'sanctum', 'broadcasting', 'graphql', 'sitemap', 'robots',
        'feed', 'rss', 'search', 'tags', 'categories', 'category',
        'admin-panel', 'backend', 'console', 'webhook', 'webhooks',
        'null', 'undefined', 'new', 'edit', 'create', 'delete', 'update',
        'checkout', 'order-success', 'track-order', 'cart', 'order', 'orders',
        'llk', 'landing', 'preview', 'lead', 'leads', 'payment', 'payments',
    ],

    /*
    |--------------------------------------------------------------------------
    | Unique Slug Lock
    |--------------------------------------------------------------------------
    |
    | Cache lock around uniqueness checks to reduce race conditions when
    | many models are created concurrently with the same title.
    |
    */
    'unique_lock' => [
        'enabled' => true,
        'seconds' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scheduled Maintenance
    |--------------------------------------------------------------------------
    |
    | When enabled, the package registers a daily `slug:prune` schedule.
    |
    */
    'schedule' => [
        'prune' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Strip HTML Tags & Emojis
    |--------------------------------------------------------------------------
    |
    | Automatically strip HTML/XML tags and emojis from the source string.
    |
    */
    'strip_tags' => true,
    'strip_emojis' => true,

    /*
    |--------------------------------------------------------------------------
    | Unique Suffix Settings (for Eloquent Models)
    |--------------------------------------------------------------------------
    |
    | When handling duplicate slugs in Eloquent models, define the separator
    | and starting index (e.g. "my-slug-1", "my-slug-2").
    |
    */
    'unique_suffix' => [
        'separator' => '-',
        'start_index' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Symbol & Character Replacements
    |--------------------------------------------------------------------------
    |
    | Custom mapping of symbols/characters to word equivalents before slugging.
    |
    */
    'replacements' => [
        '@' => 'at',
        '&' => 'and',
        '%' => 'percent',
        '#' => 'number',
        '+' => 'plus',
        '=' => 'equals',
        '€' => 'eur',
        '£' => 'gbp',
        '$' => 'usd',
        '¥' => 'jpy',
        '৳' => 'taka',
        '₹' => 'rupee',
    ],

    /*
    |--------------------------------------------------------------------------
    | Language-Specific Symbol & Word Maps
    |--------------------------------------------------------------------------
    |
    | Custom mappings tailored for specific language codes ('bn', 'ar', 'hi', etc.).
    |
    */
    'language_replacements' => [
        'bn' => [
            '&' => 'এবং',
            '@' => 'এট',
            '%' => 'শতাংশ',
            '+' => 'যোগ',
            '৳' => 'টাকা',
        ],
        'ar' => [
            '&' => 'و',
            '%' => 'في المئة',
            '+' => 'زائد',
        ],
        'hi' => [
            '&' => 'और',
            '%' => 'प्रतिशत',
            '+' => 'धन',
            '₹' => 'रुपया',
        ],
        'fr' => [
            '&' => 'et',
            '@' => 'arobase',
            '%' => 'pourcent',
            '+' => 'plus',
            '€' => 'euro',
        ],
        'es' => [
            '&' => 'y',
            '@' => 'arroba',
            '%' => 'por-ciento',
            '+' => 'mas',
            '€' => 'euro',
        ],
        'de' => [
            '&' => 'und',
            '@' => 'an',
            '%' => 'prozent',
            '+' => 'plus',
            '€' => 'euro',
        ],
    ],
];
