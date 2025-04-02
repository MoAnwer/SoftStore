<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
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

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array 
    {
        return [
            null => Tab::make('All'),
            'New' => Tab::make('New')
                    ->modifyQueryUsing(fn() => Order::query()->where('status', 'new'))
                    ->badge(Order::query()->where('status', 'new')->count())
                    ->badgeColor('info')
                    ->icon('heroicon-m-sparkles'),

            'Processing' => Tab::make('Processing')
                            ->query(fn($query) => $query->where('status', 'processing'))
                            ->badge(Order::query()->where('status', 'processing')->count())
                            ->badgeColor('warning')
                            ->icon('heroicon-m-arrow-path'),

            'Shipping' => Tab::make('Shipping')
                        ->query(fn($query) => $query->where('status', 'shipping'))
                        ->badge(Order::query()->where('status', 'shipping')->count())
                        ->badgeColor('primary')
                        ->icon('heroicon-m-truck'),
            'Delivered' => Tab::make('Delivered')
                        ->query(fn($query) => $query->where('status', 'delivered'))
                        ->badge(Order::query()->where('status', 'delivered')->count())
                        ->badgeColor('success')
                        ->icon('heroicon-m-check-badge'),
            'Cancelled' => Tab::make('Cancelled')
                        ->query(fn($query) => $query->where('status', 'cancelled'))
                        ->badge(Order::query()->where('status', 'cancelled')->count())
                        ->badgeColor('danger')
                        ->icon('heroicon-m-x-circle'),
        ];
    }
}
