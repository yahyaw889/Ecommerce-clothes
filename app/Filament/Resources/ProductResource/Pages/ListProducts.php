<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Widgets\ProductOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل'),
            'out_of_stock' => Tab::make()->query(fn($query) => $query->where('status', 0))->label('المنتجات الغير متوفرة'),
            'in_stock' => Tab::make()->query(fn($query) => $query->where('status', 1))->label('المنتجات متوفرة'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [ProductOverview::class];
    }
}
