<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Services\SectionRegistry;
use ImranDevBd\AiHub\Facades\AIHub;
use Illuminate\Support\Facades\Log;

class AiLandingGenerator
{
    public function __construct(
        protected SectionRegistry $sectionRegistry
    ) {}

    /**
     * Generate a complete structured landing page using AI.
     */
    public function generateLandingPage(array $params): array
    {
        $productName = $params['product_name'] ?? 'পণ্য';
        $category = $params['category'] ?? 'General';
        $benefits = $params['benefits'] ?? '';
        $targetAudience = $params['target_audience'] ?? 'বাঙালি ক্রেতা';
        $tone = $params['tone'] ?? 'উচ্চ রূপান্তরকারী, বিশ্বস্ত ও আকর্ষণীয়';
        $language = $params['language'] ?? 'বাংলা';

        $prompt = <<<EOT
You are an expert Bangladeshi direct-response copywriter and landing page architect.
Create a high-converting, 6-section ecommerce landing page for the following product:
- Product: {$productName}
- Category: {$category}
- Key Benefits: {$benefits}
- Target Audience: {$targetAudience}
- Tone: {$tone}
- Language: {$language}

Available section types to choose from:
- hero_image_right
- showcase_gallery_grid
- features_grid_3col
- proof_testimonial_cards
- offer_countdown
- order_form_classic
- faq_accordion
- guarantee_money_back
- footer_minimal

Return ONLY a valid JSON array of objects with keys: "section_type" and "content".
Example format:
[
  {
    "section_type": "hero_image_right",
    "content": {
      "badge": "ধামাকা অফার",
      "title": "...",
      "subtitle": "...",
      "cta_text": "এখনই অর্ডার করুন"
    }
  },
  {
    "section_type": "features_grid_3col",
    "content": {
      "title": "আমাদের বিশেষত্ব",
      "items": [
        {"title": "১০০% আসল", "desc": "..."},
        {"title": "দ্রুত ডেলিভারি", "desc": "..."},
        {"title": "সহজ রিটার্ন", "desc": "..."}
      ]
    }
  },
  {
    "section_type": "order_form_classic",
    "content": {
      "title": "অর্ডার করতে ফর্মটি পূরণ করুন",
      "subtitle": "ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে ডেলিভারি।"
    }
  }
]
EOT;

        try {
            $response = AIHub::prompt($prompt)->send();
            $rawContent = trim($response->content);

            // Strip markdown code fences if present
            if (str_starts_with($rawContent, '```')) {
                $rawContent = preg_replace('/^```(?:json)?\s*/', '', $rawContent);
                $rawContent = preg_replace('/\s*```$/', '', $rawContent);
            }

            $decoded = json_decode($rawContent, true);

            if (is_array($decoded) && count($decoded) > 0) {
                return $this->validateAndSanitizeSections($decoded);
            }
        } catch (\Throwable $e) {
            Log::warning('AI Landing Page Generation Failed, using high-converting fallback: ' . $e->getMessage());
        }

        // High-converting smart fallback tailored to the product
        return $this->fallbackLandingPage($productName, $benefits);
    }

    /**
     * Generate content for an individual section.
     */
    public function generateSection(string $sectionType, array $context): array
    {
        $def = $this->sectionRegistry->get($sectionType);
        $defaults = $def ? $def->defaults() : [];
        $productName = $context['product_name'] ?? 'আমাদের পণ্য';

        $prompt = "Write persuasive Bangla copy for a {$sectionType} section of a landing page for '{$productName}'. Return ONLY a JSON object matching this structure: " . json_encode($defaults, JSON_UNESCAPED_UNICODE);

        try {
            $response = AIHub::prompt($prompt)->send();
            $raw = trim($response->content);
            if (str_starts_with($raw, '```')) {
                $raw = preg_replace('/^```(?:json)?\s*/', '', $raw);
                $raw = preg_replace('/\s*```$/', '', $raw);
            }
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return array_merge($defaults, $decoded);
            }
        } catch (\Throwable $e) {
            // Handled
        }

        return $defaults;
    }

    /**
     * Rewrite or improve copy.
     */
    public function rewriteCopy(string $text, string $mode = 'more_persuasive'): string
    {
        $instruction = match ($mode) {
            'shorter' => 'Make this copy more concise and punchy without losing key selling points.',
            'more_urgent' => 'Add psychological urgency and FOMO (fear of missing out) suited for Bangladeshi ecommerce.',
            'fix_grammar' => 'Correct any spelling, phrasing, or grammar issues in natural Bengali.',
            'translate_bn' => 'Translate and adapt this into natural, colloquial Bangladeshi ecommerce Bengali.',
            default => 'Make this copy significantly more persuasive, emotional, and benefit-focused.',
        };

        $prompt = "{$instruction}\n\nOriginal text:\n\"{$text}\"\n\nReturn ONLY the revised text with no explanation or quotes.";

        try {
            $response = AIHub::prompt($prompt)->send();
            return trim($response->content);
        } catch (\Throwable $e) {
            return $text;
        }
    }

    /**
     * Generate SEO meta title and description.
     */
    public function generateSeoMeta(string $productName, string $benefits = ''): array
    {
        $prompt = "Generate high-CTR SEO meta title (under 60 chars) and meta description (under 155 chars) in Bengali for: {$productName}. Benefits: {$benefits}. Return ONLY JSON with keys: seo_title, seo_description.";

        try {
            $res = AIHub::prompt($prompt)->send();
            $raw = trim($res->content);
            if (str_starts_with($raw, '```')) {
                $raw = preg_replace('/^```(?:json)?\s*/', '', $raw);
                $raw = preg_replace('/\s*```$/', '', $raw);
            }
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && isset($decoded['seo_title'])) {
                return $decoded;
            }
        } catch (\Throwable $e) {
            // Handled
        }

        return [
            'seo_title' => "{$productName} - সেরা দামে কিনুন | ক্যাশ অন ডেলিভারি",
            'seo_description' => "সারা বাংলাদেশে ক্যাশ অন ডেলিভারিতে অর্ডার করুন ১০০% অরিজিনাল {$productName}। দ্রুততম ডেলিভারি ও মানিব্যাক গ্যারান্টি।",
        ];
    }

    /**
     * Suggest creative image prompts for a section.
     */
    public function suggestImagePrompts(string $sectionType, string $productName): array
    {
        return [
            "High resolution commercial product photography of {$productName}, clean minimalist studio lighting, 8k, photorealistic",
            "Bangladeshi customer smiling holding package of {$productName}, natural sunlight, authentic warm tone, Canon 85mm f/1.4",
            "Macro close up detail shot of {$productName} highlighting premium craftsmanship, textured background, depth of field",
        ];
    }

    /**
     * Validate and sanitize AI section blueprints.
     */
    protected function validateAndSanitizeSections(array $sections): array
    {
        $sanitized = [];

        foreach ($sections as $sec) {
            if (!isset($sec['section_type']) || !is_string($sec['section_type'])) {
                continue;
            }

            $type = $sec['section_type'];
            // Check against section registry to disallow unknown types
            if (!$this->sectionRegistry->has($type)) {
                continue;
            }

            $def = $this->sectionRegistry->get($type);
            $rawContent = $sec['content'] ?? [];

            // Sanitize content: strip dangerous tags
            $cleanContent = $this->sanitizeContentArray(is_array($rawContent) ? $rawContent : []);

            $sanitized[] = [
                'section_type' => $type,
                'content' => array_merge($def->defaults(), $cleanContent),
                'style' => [
                    'bg_color' => '#ffffff',
                    'padding_top' => 'py-12',
                ],
                'responsive' => [
                    'desktop' => true,
                    'mobile' => true,
                ],
            ];
        }

        return count($sanitized) > 0 ? $sanitized : $this->fallbackLandingPage('পণ্য');
    }

    /**
     * Recursively sanitize content array.
     */
    protected function sanitizeContentArray(array $array): array
    {
        $clean = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $clean[$key] = $this->sanitizeContentArray($value);
            } elseif (is_string($value)) {
                // Strip scripts, php tags, on* handlers
                $sanitized = strip_tags($value, '<p><br><b><strong><i><em><ul><ol><li><span>');
                $sanitized = preg_replace('/javascript:/i', '', $sanitized);
                $clean[$key] = $sanitized;
            } else {
                $clean[$key] = $value;
            }
        }
        return $clean;
    }

    /**
     * Fallback landing page blueprint.
     */
    protected function fallbackLandingPage(string $productName, string $benefits = ''): array
    {
        return [
            [
                'section_type' => 'hero_image_right',
                'content' => [
                    'badge' => 'ধামাকা অফার',
                    'title' => "১০০% প্রিমিয়াম কোয়ালিটি {$productName}",
                    'subtitle' => 'সারা বাংলাদেশে দ্রুততম হোম ডেলিভারি এবং পণ্য হাতে পেয়ে ক্যাশ অন ডেলিভারিতে মূল্য পরিশোধের সুবিধা।',
                    'cta_text' => 'এখনই অর্ডার করুন',
                ],
            ],
            [
                'section_type' => 'features_grid_3col',
                'content' => [
                    'title' => 'কেন আমাদের পণ্যটি সবার সেরা?',
                    'items' => [
                        ['title' => '১০০% খাঁটি ও অরিজিনাল', 'desc' => 'মানের ব্যাপারে কোনো আপস নেই।'],
                        ['title' => 'দ্রুত ডেলিভারি', 'desc' => '২৪ থেকে ৭২ ঘণ্টার মধ্যে পৌঁছে যাবে আপনার ঠিকানায়।'],
                        ['title' => 'সহজ রিটার্ন সুবিধা', 'desc' => 'পণ্য পছন্দ না হলে তাৎক্ষণিক রিটার্নের সুযোগ।'],
                    ],
                ],
            ],
            [
                'section_type' => 'proof_testimonial_cards',
                'content' => [
                    'title' => 'আমাদের সন্তুষ্ট গ্রাহকদের মতামত',
                ],
            ],
            [
                'section_type' => 'order_form_classic',
                'content' => [
                    'title' => 'অর্ডার করতে নিচের ফর্মটি পূরণ করুন',
                    'subtitle' => 'ক্যাশ অন ডেলিভারিতে পণ্য পৌঁছাবে আপনার দরজায়।',
                ],
            ],
            [
                'section_type' => 'footer_minimal',
                'content' => [
                    'copyright' => 'সর্বস্বত্ব সংরক্ষিত।',
                ],
            ],
        ];
    }
}
