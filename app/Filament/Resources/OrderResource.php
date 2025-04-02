<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\{Repeater, Grid, Hidden, Placeholder, Section, Select, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\{SelectColumn, TextColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                ->schema([
                    Section::make('Order information')
                    ->schema([
                        Select::make('user_id')
                        ->relationship('user',  'name')
                        ->native(false)
                        ->preload()
                        ->searchable()
                        ->required(),
                        Select::make('payment_method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cod'   => 'Cash on delivery'
                        ])
                        ->native(false)
                        ->searchable()
                        ->required(),

                        Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid'   => 'paid',
                            'failed'   => 'Failed',

                        ])
                        ->native(false)
                        ->default('pending'),

                        ToggleButtons::make('status')
                            ->inline()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipping' => 'Shipping',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled'
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipping' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipping' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle'
                            ])
                            ->default('new')

                    ])
                    ->columnSpan(4),

                    Section::make('Money Details')
                        ->schema([
                            Select::make('currency')
                                ->options([
                                    'sdg' => 'SDG',
                                    'usd' => 'USD'
                                ])
                                ->native(false)
                                ->required(),

                            Select::make('shipping_method')
                                ->options([
                                    'fedex' => 'Fedex',
                                    'ups' => 'UPS',
                                    'dhl' => 'DHL',
                                    'usps' => 'USPS'
                                ])
                                ->native(false)
                                ->required(),

                            Textarea::make('notes')

                        ])
                        ->columnSpan(2)

                ])
                ->columns(6),

                Section::make('Order items')
                    ->schema([
                       Repeater::make('items') 
                       ->relationship()
                       ->schema([

                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->required()
                                ->distinct()
                                ->columnSpan(5)
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->afterStateUpdated(
                                    fn($state, Set $set) => 
                                    $set('unit_amount', Product::find($state)?->price ?? 0)
                                )
                                ->afterStateUpdated(
                                    fn($state, Set $set) => 
                                    $set('total_amount', Product::find($state)?->price ?? 0)
                                ),

                            TextInput::make('quantity')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->reactive()
                                ->afterStateUpdated(
                                    function($state, Set $set, Get $get) {
                                        return $set('total_amount', $get('unit_amount') * $state);
                                    }
                                )
                                ->columnSpan(2),

                            TextInput::make('unit_amount')
                                ->columnSpan(2)
                                ->numeric()
                                ->required()
                                ->disabled(),

                            TextInput::make('total_amount')
                                ->columnSpan(3)
                                ->numeric()
                                ->required()
                                ->disabled(),
                            
                        ])
                        ->columns(12),

                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function (Get $get, Set $set){
                                $total = 0;
                                if(!$repeaters = $get('items')) {
                                    return $total;
                                }
                                
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_amount");
                                }

                                $set('grand_total', $total);
                                return Number::currency($total, 'SDG');
                            }),

                        Hidden::make('grand_total')->default(0)
                    ]),
            ]);
                            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Custmor')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('SDG'),
                TextColumn::make('payment_method')
                    ->searchable(),
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'paid'     => 'success',
                        'pending'  => 'warning',
                        'failed'   => 'danger',
                    })
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('shipping_method')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge() : ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'primary';
    }


    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}