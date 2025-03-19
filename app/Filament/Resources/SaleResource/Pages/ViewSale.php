<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Sale Details')->schema([
                TextInput::make('user.name')
                    ->label('Cashier')
                    ->disabled(),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->formatStateUsing(fn ($state) => '₹' . number_format($state, 2))
                    ->disabled(),

                TextInput::make('discount')
                    ->label('Discount')
                    ->formatStateUsing(fn ($state) => '₹' . number_format($state, 2))
                    ->disabled(),

                TextInput::make('final_amount')
                    ->label('Final Amount')
                    ->formatStateUsing(fn ($state) => '₹' . number_format($state, 2))
                    ->disabled(),

                TextInput::make('payment_method')
                    ->label('Payment Method')
                    ->disabled(),
            ]),

            Section::make('Sale Items')->schema([
                Repeater::make('saleItems')
                    ->relationship('saleItems')
                    ->schema([
                        TextInput::make('stockEntry.product.name')
                            ->label('Product')
                            ->disabled(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('price')
                            ->label('Selling Price')
                            ->formatStateUsing(fn ($state) => '₹' . number_format($state, 2))
                            ->disabled(),
                    ])
                    ->columns(3)
                    ->disableItemCreation()
                    ->disableItemDeletion()
            ])
        ];
    }
}
