<?php

namespace App\Filament\Resources\DesktopResource\Pages;

use App\Filament\Resources\DesktopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDesktop extends ViewRecord
{
    protected static string $resource = DesktopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
