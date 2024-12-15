<?php

namespace App\Filament\Resources\SpecialResource\Pages;

use App\Filament\Resources\SpecialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
 class ListSpecials extends ListRecords
{
    protected static string $resource = SpecialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array{
        return[
              null=>Tab::make('الكل'),
              'الطلبات اللتي لم يتم تسلمها' => Tab::make()->query(fn($query) => $query->where('status',0)), 
              'الطلبات اللتي تم تسلمها' => Tab::make()->query(fn($query) => $query->where('status',1)), 
                   
            ];
        }
}
