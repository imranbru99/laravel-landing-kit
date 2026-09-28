<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\PageRevision;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\SavedSection;
use App\Models\Template;
use App\Services\AI\AiLandingGenerator;
use App\Services\SectionRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingPageBuilderController extends Controller
{
    public function __construct(
        protected SectionRegistry $sectionRegistry
    ) {}

    /**
     * Launch or find builder for a product.
     */
    public function builderForProduct(Product $product): RedirectResponse
    {
        $landingPage = $product->landingPage;

        if (! $landingPage) {
            $landingPage = LandingPage::create([
                'product_id' => $product->id,
                'title' => $product->name.' Landing Page',
                'status' => 'draft',
                'theme_tokens' => [
                    'primary_color' => '#10b981',
                    'accent_color' => '#f59e0b',
                    'font_family' => 'Hind Siliguri',
                    'radius' => 'rounded-xl',
                ],
                'settings' => [
                    'sticky_cta' => true,
                    'floating_whatsapp' => false,
                ],
            ]);

            // Add basic default sections
            $defaultTypes = ['hero_image_right', 'showcase_gallery_grid', 'features_grid_3col', 'proof_review_wall', 'order_form_classic', 'footer_minimal'];
            foreach ($defaultTypes as $index => $type) {
                $sectionDef = $this->sectionRegistry->get($type);
                PageSection::create([
                    'landing_page_id' => $landingPage->id,
                    'section_type' => $type,
                    'position' => $index,
                    'is_visible' => true,
                    'content' => $sectionDef ? $sectionDef->defaults() : ['title' => 'Sample Title'],
                    'style' => ['padding_top' => 'py-12', 'bg_color' => '#ffffff'],
                    'responsive' => ['desktop' => true, 'mobile' => true],
                ]);
            }

            $landingPage->createRevision(auth()->user(), 'Initial Draft');
        }

        return redirect()->route('admin.builder.index', $landingPage);
    }

    /**
     * Render the 3-pane Visual Page Builder.
     */
    public function builder(LandingPage $landingPage): View
    {
        $landingPage->load(['product.images', 'product.variants', 'product.offers', 'sections' => fn ($q) => $q->orderBy('position'), 'revisions']);

        $categories = $this->sectionRegistry->categories();
        $sections = $this->sectionRegistry->all();
        $savedSections = SavedSection::latest()->get();

        $currentSections = $landingPage->sections->map(fn ($s) => [
            'id' => $s->id,
            'section_type' => $s->section_type,
            'label' => Str::headline($s->section_type),
            'position' => $s->position,
            'is_visible' => $s->is_visible,
            'content' => $s->content ?? [],
            'style' => $s->style ?? [],
            'responsive' => $s->responsive ?? [],
        ])->values()->toArray();

        return view('admin.builder.index', compact(
            'landingPage',
            'categories',
            'sections',
            'savedSections',
            'currentSections'
        ));
    }

    /**
     * Render the Live Canvas iframe contents.
     */
    public function canvas(LandingPage $landingPage): View
    {
        $landingPage->load(['product.images', 'product.variants', 'product.offers', 'sections' => fn ($q) => $q->where('is_visible', true)->orderBy('position')]);

        $renderedSections = [];
        foreach ($landingPage->sections as $pageSection) {
            $html = $this->sectionRegistry->render(
                $pageSection->section_type,
                $pageSection->content ?? [],
                $pageSection->style ?? [],
                [
                    'product' => $landingPage->product,
                    'landingPage' => $landingPage,
                    'pageSection' => $pageSection,
                ]
            );

            $renderedSections[] = [
                'id' => $pageSection->id,
                'section_type' => $pageSection->section_type,
                'label' => $this->sectionRegistry->get($pageSection->section_type)?->label() ?? $pageSection->section_type,
                'html' => $html,
            ];
        }

        return view('admin.builder.canvas', compact('landingPage', 'renderedSections'));
    }

    /**
     * Public / Admin full page preview.
     */
    public function preview(LandingPage $landingPage): View
    {
        return $this->canvas($landingPage);
    }

    /**
     * Add a section to the landing page.
     */
    public function addSection(Request $request, LandingPage $landingPage): JsonResponse
    {
        $request->validate([
            'section_type' => 'required|string',
            'position' => 'nullable|integer',
        ]);

        $sectionType = $request->input('section_type');
        $def = $this->sectionRegistry->get($sectionType);

        if (! $def) {
            return response()->json(['error' => 'Invalid section type'], 422);
        }

        $maxPos = $landingPage->sections()->max('position') ?? -1;
        $position = $request->has('position') ? (int) $request->input('position') : $maxPos + 1;

        $newSection = PageSection::create([
            'landing_page_id' => $landingPage->id,
            'section_type' => $sectionType,
            'position' => $position,
            'is_visible' => true,
            'content' => $def->defaults(),
            'style' => [
                'bg_color' => '#ffffff',
                'text_color' => '#1e293b',
                'padding_top' => 'py-12',
            ],
            'responsive' => [
                'desktop' => true,
                'mobile' => true,
            ],
        ]);

        return response()->json([
            'success' => true,
            'section' => $newSection,
            'label' => $def->label(),
        ]);
    }

    /**
     * Reorder sections on canvas.
     */
    public function reorderSections(Request $request, LandingPage $landingPage): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $order = $request->input('order');

        DB::transaction(function () use ($landingPage, $order) {
            foreach ($order as $index => $sectionId) {
                PageSection::where('landing_page_id', $landingPage->id)
                    ->where('id', $sectionId)
                    ->update(['position' => $index]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Update section content and styles.
     */
    public function updateSection(Request $request, LandingPage $landingPage, PageSection $section): JsonResponse
    {
        $request->validate([
            'content' => 'nullable|array',
            'style' => 'nullable|array',
            'responsive' => 'nullable|array',
        ]);

        $section->update([
            'content' => $request->input('content', $section->content),
            'style' => $request->input('style', $section->style),
            'responsive' => $request->input('responsive', $section->responsive),
        ]);

        return response()->json([
            'success' => true,
            'section' => $section,
        ]);
    }

    /**
     * Duplicate a section.
     */
    public function duplicateSection(Request $request, LandingPage $landingPage, PageSection $section): JsonResponse
    {
        $newSection = PageSection::create([
            'landing_page_id' => $landingPage->id,
            'section_type' => $section->section_type,
            'position' => $section->position + 1,
            'is_visible' => $section->is_visible,
            'content' => $section->content,
            'style' => $section->style,
            'responsive' => $section->responsive,
        ]);

        // Re-index subsequent sections
        PageSection::where('landing_page_id', $landingPage->id)
            ->where('id', '!=', $newSection->id)
            ->where('position', '>', $section->position)
            ->increment('position');

        return response()->json([
            'success' => true,
            'section' => $newSection,
        ]);
    }

    /**
     * Delete a section.
     */
    public function deleteSection(Request $request, LandingPage $landingPage, PageSection $section): JsonResponse
    {
        $section->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Toggle visibility of a section.
     */
    public function toggleVisibility(Request $request, LandingPage $landingPage, PageSection $section): JsonResponse
    {
        $section->is_visible = ! $section->is_visible;
        $section->save();

        return response()->json([
            'success' => true,
            'is_visible' => $section->is_visible,
        ]);
    }

    /**
     * Save a section as a reusable template.
     */
    public function saveAsReusable(Request $request, LandingPage $landingPage, PageSection $section): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $saved = SavedSection::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'section_type' => $section->section_type,
            'category' => $this->sectionRegistry->get($section->section_type)?->category() ?? 'custom',
            'content' => $section->content,
            'style' => $section->style,
        ]);

        return response()->json([
            'success' => true,
            'saved_section' => $saved,
        ]);
    }

    /**
     * List saved sections.
     */
    public function savedSections(): JsonResponse
    {
        return response()->json([
            'saved_sections' => SavedSection::latest()->get(),
        ]);
    }

    /**
     * Insert saved section into page.
     */
    public function insertSavedSection(Request $request, LandingPage $landingPage): JsonResponse
    {
        $request->validate([
            'saved_section_id' => 'required|exists:saved_sections,id',
        ]);

        $saved = SavedSection::findOrFail($request->input('saved_section_id'));
        $maxPos = $landingPage->sections()->max('position') ?? -1;

        $newSection = PageSection::create([
            'landing_page_id' => $landingPage->id,
            'section_type' => $saved->section_type,
            'position' => $maxPos + 1,
            'is_visible' => true,
            'content' => $saved->content,
            'style' => $saved->style,
            'responsive' => ['desktop' => true, 'mobile' => true],
        ]);

        return response()->json([
            'success' => true,
            'section' => $newSection,
        ]);
    }

    /**
     * Create revision snapshot.
     */
    public function createRevision(Request $request, LandingPage $landingPage): JsonResponse
    {
        $revision = $landingPage->createRevision(
            auth()->user(),
            $request->input('title', 'Manual revision '.now()->format('H:i:s'))
        );

        return response()->json([
            'success' => true,
            'revision' => $revision,
        ]);
    }

    /**
     * Restore from revision snapshot.
     */
    public function restoreRevision(Request $request, LandingPage $landingPage, PageRevision $revision): JsonResponse
    {
        $landingPage->restoreRevision($revision);

        return response()->json([
            'success' => true,
            'message' => 'Revision restored successfully',
        ]);
    }

    /**
     * Update page theme tokens & settings.
     */
    public function updatePageSettings(Request $request, LandingPage $landingPage): JsonResponse
    {
        $landingPage->update([
            'title' => $request->input('title', $landingPage->title),
            'theme_tokens' => $request->input('theme_tokens', $landingPage->theme_tokens),
            'settings' => $request->input('settings', $landingPage->settings),
            'custom_css' => $request->input('custom_css', $landingPage->custom_css),
            'custom_js' => $request->input('custom_js', $landingPage->custom_js),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Publish or unpublish landing page.
     */
    public function publish(Request $request, LandingPage $landingPage): JsonResponse
    {
        $publish = $request->boolean('publish', true);

        $landingPage->update([
            'status' => $publish ? 'published' : 'draft',
            'published_at' => $publish ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'status' => $landingPage->status,
        ]);
    }

    /**
     * AI: Generate full landing page and save as draft.
     */
    public function generateFullPage(Request $request, LandingPage $landingPage, AiLandingGenerator $generator): JsonResponse
    {
        $params = [
            'product_name' => $request->input('product_name', $landingPage->product?->name ?? 'আমাদের পণ্য'),
            'category' => $request->input('category', $landingPage->product?->categories?->first()?->name ?? 'Ecommerce'),
            'benefits' => $request->input('benefits', $landingPage->product?->short_description ?? ''),
            'target_audience' => $request->input('target_audience', 'Bangladeshi online shoppers'),
            'tone' => $request->input('tone', 'persuasive, high converting, authentic'),
            'language' => $request->input('language', 'bn'),
        ];

        // Backup existing state
        $landingPage->createRevision(auth()->user(), 'Before AI Generation');

        $sectionsData = $generator->generateLandingPage($params);

        DB::transaction(function () use ($landingPage, $sectionsData) {
            $landingPage->sections()->delete();
            $landingPage->update(['status' => 'draft']);

            foreach ($sectionsData as $index => $item) {
                PageSection::create([
                    'landing_page_id' => $landingPage->id,
                    'section_type' => $item['section_type'],
                    'position' => $index,
                    'is_visible' => true,
                    'content' => $item['content'],
                    'style' => $item['style'],
                    'responsive' => $item['responsive'],
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'AI landing page generated successfully and saved as draft.',
            'sections_count' => count($sectionsData),
        ]);
    }

    /**
     * AI: Rewrite or improve section copy.
     */
    public function rewriteCopy(Request $request, AiLandingGenerator $generator): JsonResponse
    {
        $text = (string) $request->input('text', '');
        $mode = (string) $request->input('mode', 'more_persuasive');

        if (empty($text)) {
            return response()->json(['error' => 'Text required'], 422);
        }

        $revised = $generator->rewriteCopy($text, $mode);

        return response()->json([
            'success' => true,
            'revised_text' => $revised,
        ]);
    }

    /**
     * Apply a pre-built template to the current landing page.
     */
    public function applyTemplate(Request $request, LandingPage $landingPage, Template $template): JsonResponse
    {
        $landingPage->applyTemplate($template);

        return response()->json([
            'success' => true,
            'message' => 'Template applied successfully!',
        ]);
    }
}
