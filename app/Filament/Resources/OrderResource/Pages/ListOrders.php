<?php
namespace App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\orderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
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

    protected function getHeaderWidgets(): array{
        return [ orderStats::class ];
    }

}
