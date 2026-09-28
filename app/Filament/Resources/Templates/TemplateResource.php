<?php

declare(strict_types=1);

namespace App\Filament\Resources\Templates;

use App\Filament\Resources\Templates\Pages\ListTemplates;
use App\Models\Template;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|UnitEnum|null $navigationGroup = 'Landing Pages & Builder';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Templates Library (100+)';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Template Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fashion' => 'danger',
                        'beauty' => 'warning',
                        'health', 'grocery' => 'success',
                        'electronics', 'automotive' => 'info',
                        'universal' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('palette')
                    ->label('Theme Palette')
                    ->formatStateUsing(function (Template $record) {
                        $p = $record->palette['primary_color'] ?? '#10b981';
                        $a = $record->palette['accent_color'] ?? '#f59e0b';

                        return "<div class='flex items-center space-x-1.5'><span class='w-4 h-4 rounded-full border border-slate-300' style='background-color: {$p}'></span><span class='w-4 h-4 rounded-full border border-slate-300' style='background-color: {$a}'></span></div>";
                    })
                    ->html(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->fontFamily('mono')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'fashion' => 'Fashion & Apparel',
                        'footwear' => 'Footwear & Bags',
                        'beauty' => 'Beauty & Personal Care',
                        'health' => 'Health & Wellness',
                        'grocery' => 'Grocery & Food',
                        'electronics' => 'Electronics & Gadgets',
                        'home' => 'Home & Living',
                        'kids' => 'Kids & Baby',
                        'books' => 'Books & Education',
                        'automotive' => 'Automotive',
                        'gifts' => 'Gifts & Occasions',
                        'craft' => 'Handicraft & Local',
                        'universal' => 'Universal High-Converting',
                    ]),
            ])
            ->recordActions([
                Action::make('export')
                    ->label('Export JSON')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function (Template $record) {
                        $data = [
                            'name' => $record->name,
                            'slug' => $record->slug,
                            'category' => $record->category,
                            'description' => $record->description,
                            'palette' => $record->palette,
                            'tags' => $record->tags,
                            'sections' => $record->sections->map(fn ($s) => [
                                'section_type' => $s->section_type,
                                'position' => $s->position,
                                'content' => $s->content,
                            ])->toArray(),
                        ];

                        return response()->streamDownload(function () use ($data) {
                            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }, "template-{$record->slug}.json", ['Content-Type' => 'application/json']);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTemplates::route('/'),
        ];
    }
}
