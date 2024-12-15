<?php

namespace App\Filament\Resources\ModelNameResource\Pages;

use App\Filament\Resources\ModelNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelNames extends ListRecords
{
    protected static string $resource = ModelNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
