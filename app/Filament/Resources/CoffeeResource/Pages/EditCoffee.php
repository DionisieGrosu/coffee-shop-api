<?php

namespace App\Filament\Resources\CoffeeResource\Pages;

use App\Filament\Resources\CoffeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoffee extends EditRecord
{
    protected static string $resource = CoffeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
