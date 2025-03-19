<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LowStockAlertResource\Pages\ListLowStockAlerts;
use App\Models\LowStockAlert;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class LowStockAlertResource extends Resource
{
    protected static ?string $model = LowStockAlert::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Low Stock Alerts';
    protected static ?string $pluralLabel = 'Low Stock Alerts';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('current_stock')
                    ->label('Current Stock')
                    ->sortable(),

                TextColumn::make('threshold') // Ensure this matches your model
                    ->label('Stock Alert Threshold')
                    ->sortable(),

                TextColumn::make('alert_sent')
                    ->label('Alert Sent')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLowStockAlerts::route('/'),
        ];
    }
}
