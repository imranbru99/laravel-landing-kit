<?php

declare(strict_types=1);

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Commands\Concerns\CanManipulateFiles;

if (! trait_exists('Filament\Commands\Concerns\CanManipulateFiles', false) && trait_exists('Filament\Support\Commands\Concerns\CanManipulateFiles')) {
    class_alias(CanManipulateFiles::class, 'Filament\Commands\Concerns\CanManipulateFiles');
}

// Filament v4 Schema compatibility aliases for legacy component namespaces
if (! class_exists('Filament\Forms\Components\Tabs', false) && class_exists('Filament\Schemas\Components\Tabs')) {
    class_alias(Tabs::class, 'Filament\Forms\Components\Tabs');
}

if (! class_exists('Filament\Forms\Components\Section', false) && class_exists('Filament\Schemas\Components\Section')) {
    class_alias(Section::class, 'Filament\Forms\Components\Section');
}

if (! class_exists('Filament\Forms\Components\Grid', false) && class_exists('Filament\Schemas\Components\Grid')) {
    class_alias(Grid::class, 'Filament\Forms\Components\Grid');
}
