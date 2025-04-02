<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\{RelationManagers, Pages};
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\{FileUpload, Section, TextInput, Group, MarkdownEditor, Toggle, Textarea, Select};
use Filament\Forms\{Set, Form};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                ->schema([
                    Section::make('Product information')
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                function (string $operation, $state, Set $set) { 
                                    $operation === 'create' ? $set('slug', Str::slug($state)) : null;
                                }
                            )
                            ->required(),

                        TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ->required(),

                        MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('products')

                    ])->columns(2),

                    Section::make('Images')
                    ->schema([
                        FileUpload::make('images')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                    ])
                ])->columnSpan(2),

                Group::make()
                ->schema([
                    Section::make('Price')
                    ->schema([
                        TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->prefix('SDG')
                    ]),
                    Section::make('Associations')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->loadingMessage('pleace wait...')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('brand_id')
                            ->loadingMessage('pleace wait...')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->relationship('brand', 'name')
                            ->required(),
                    ]),
                    Section::make('Status')
                    ->schema([
                        Toggle::make('is_stock')
                            ->required()
                            ->default(true),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Toggle::make('is_featured')
                            ->required(),
                        Toggle::make('on_sale')
                            ->required(),
                    ])
                ])
                ->columnSpan(1)
                    
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),               
                Tables\Columns\TextColumn::make('price')
                    ->money('SDG')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->native(false)
                    ->searchable()
                    ->relationship('category', 'name')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
