<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateSection;
use App\Services\TemplateCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = TemplateCatalog::getTemplates();

        DB::transaction(function () use ($templates) {
            foreach ($templates as $item) {
                /** @var Template $template */
                $template = Template::updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'description' => $item['description'],
                        'tags' => $item['tags'],
                        'palette' => $item['palette'],
                        'default_language' => 'bn',
                        'is_active' => true,
                    ]
                );

                // Re-seed template sections
                $template->sections()->delete();

                foreach ($item['sections'] as $sec) {
                    TemplateSection::create([
                        'template_id' => $template->id,
                        'section_type' => $sec['section_type'],
                        'position' => $sec['position'],
                        'content' => $sec['content'],
                        'style' => [
                            'bg_color' => '#ffffff',
                            'padding_top' => 'py-12',
                        ],
                        'responsive' => [
                            'desktop' => true,
                            'mobile' => true,
                        ],
                    ]);
                }
            }
        });
    }
}
