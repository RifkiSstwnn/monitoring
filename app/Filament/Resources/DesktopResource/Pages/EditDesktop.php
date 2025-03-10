<?php

namespace App\Filament\Resources\DesktopResource\Pages;

use App\Filament\Resources\DesktopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesktop extends EditRecord
{
    protected static string $resource = DesktopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
