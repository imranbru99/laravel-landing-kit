<?php

declare(strict_types=1);

if (!trait_exists('Filament\Commands\Concerns\CanManipulateFiles', false) && trait_exists('Filament\Support\Commands\Concerns\CanManipulateFiles')) {
    class_alias(\Filament\Support\Commands\Concerns\CanManipulateFiles::class, 'Filament\Commands\Concerns\CanManipulateFiles');
}
