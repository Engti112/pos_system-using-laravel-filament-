<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpiryAlertResource\Pages;
use App\Models\ExpiryAlert;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ExpiryAlertResource extends Resource
{
    protected static ?string $model = ExpiryAlert::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Forms\Components\Select::make('stock_entry_id')
                    ->relationship('stockEntry', 'id')
                    ->label('Stock Entry')
                    ->required(),
                Forms\Components\DatePicker::make('expiry_date')
                    ->required(),
                Forms\Components\Toggle::make('alert_sent')
                    ->label('Alert Sent')
                    ->default(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product Name')->sortable()->searchable(),
                TextColumn::make('stockEntry.id')->label('Stock Entry ID')->sortable(),
                TextColumn::make('expiry_date')->label('Expiry Date')->sortable(),
                TextColumn::make('alert_sent')->label('Alert Sent')->sortable(),
            ])
            ->filters([])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpiryAlerts::route('/'),
        ];
    }
}
