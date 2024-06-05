<?php

namespace App\Filament\Resources\CoffeeResource\Pages;

use App\Filament\Resources\CoffeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCoffee extends ViewRecord
{
    protected static string $resource = CoffeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
