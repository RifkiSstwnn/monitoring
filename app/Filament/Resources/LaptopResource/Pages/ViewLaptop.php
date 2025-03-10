<?php

namespace App\Filament\Resources\LaptopResource\Pages;

use App\Filament\Resources\LaptopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaptop extends ViewRecord
{
    protected static string $resource = LaptopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
