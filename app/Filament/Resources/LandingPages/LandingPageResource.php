<?php

declare(strict_types=1);

namespace App\Filament\Resources\LandingPages;

use App\Filament\Resources\LandingPages\Pages\ListLandingPages;
use App\Models\LandingPage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-window';

    protected static string|UnitEnum|null $navigationGroup = 'Landing Pages & Builder';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'All Landing Pages';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder('Custom Page'),

                TextColumn::make('title')
                    ->label('Page Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->placeholder('Unpublished'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
                        'archived' => 'Archived',
                    ]),
            ])
            ->recordActions([
                Action::make('builder')
                    ->label('Launch Builder')
                    ->icon('heroicon-o-paint-brush')
                    ->color('success')
                    ->url(fn (LandingPage $record): string => route('admin.builder.index', $record))
                    ->openUrlInNewTab(),
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (LandingPage $record): string => route('admin.builder.preview', $record))
                    ->openUrlInNewTab(),
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
            'index' => ListLandingPages::route('/'),
        ];
    }
}
