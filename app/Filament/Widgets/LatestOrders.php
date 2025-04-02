<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected array|string|int $columnSpan = 'full';
    protected static ?int $sort = 4;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID'),
                TextColumn::make('user.name')
                    ->label('Custmor'),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->money('SDG'),
                TextColumn::make('status')
                    ->badge()
                    ->icon(fn($state) => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipping' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle'
                    })
                    ->color(fn($state) => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipping' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'paid'     => 'success',
                        'pending'  => 'warning',
                        'failed'   => 'danger',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(format: 'd/m/Y H:i:sa')
                    ->label('Order Date')
                    ->sortable()
            ]);
    }
}
