<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use App\Models\Template;
use App\Models\TemplateSection;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_json')
                ->label('Import Template (JSON)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('template_file')
                        ->label('Template JSON File')
                        ->required()
                        ->acceptedFileTypes(['application/json']),
                ])
                ->action(function (array $data) {
                    $filePath = storage_path('app/public/'.$data['template_file']);
                    if (! file_exists($filePath)) {
                        Notification::make()->title('File not found')->danger()->send();

                        return;
                    }

                    $raw = file_get_contents($filePath);
                    $decoded = json_decode($raw, true);

                    if (! $decoded || ! isset($decoded['name'])) {
                        Notification::make()->title('Invalid template format')->danger()->send();

                        return;
                    }

                    DB::transaction(function () use ($decoded) {
                        $template = Template::create([
                            'name' => $decoded['name'],
                            'slug' => ($decoded['slug'] ?? Str::slug($decoded['name'])).'-'.uniqid(),
                            'category' => $decoded['category'] ?? 'universal',
                            'description' => $decoded['description'] ?? null,
                            'palette' => $decoded['palette'] ?? null,
                            'tags' => $decoded['tags'] ?? [],
                        ]);

                        if (! empty($decoded['sections'])) {
                            foreach ($decoded['sections'] as $index => $sec) {
                                TemplateSection::create([
                                    'template_id' => $template->id,
                                    'section_type' => $sec['section_type'],
                                    'position' => $index,
                                    'content' => $sec['content'] ?? [],
                                ]);
                            }
                        }
                    });

                    Notification::make()->title('Template imported successfully!')->success()->send();
                }),
        ];
    }
}
