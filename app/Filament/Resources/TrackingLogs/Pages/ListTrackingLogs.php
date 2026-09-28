<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrackingLogs\Pages;

use App\Filament\Resources\TrackingLogs\TrackingLogResource;
use App\Services\Tracking\TrackingManager;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTrackingLogs extends ListRecords
{
    protected static string $resource = TrackingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send_test_event')
                ->label('Send Test Event')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action(function (TrackingManager $manager) {
                    $manager->sendTestEvent('meta_capi');

                    Notification::make()
                        ->title('Test event dispatched!')
                        ->body('Dispatched test PageView event. Check the logs below for delivery status.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
