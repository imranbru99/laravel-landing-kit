<?php

declare(strict_types=1);
use Filament\Support\Commands\Concerns\CanManipulateFiles;

if (! trait_exists('Filament\Commands\Concerns\CanManipulateFiles', false) && trait_exists('Filament\Support\Commands\Concerns\CanManipulateFiles')) {
    class_alias(CanManipulateFiles::class, 'Filament\Commands\Concerns\CanManipulateFiles');
}
