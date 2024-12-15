<?php

namespace App\Filament\Resources\ModelNameResource\Pages;

use App\Filament\Resources\ModelNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelName extends EditRecord
{
    protected static string $resource = ModelNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
