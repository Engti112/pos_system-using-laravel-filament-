<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('barcode')
                    ->label('Barcode')
                    ->unique(Product::class) // Ensure barcode is unique per product
                    ->required()
                    ->maxLength(50),

                Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
            ]);
    }

    public function getViewData(): array
    {
        return [
            'recentProducts' => Product::latest()->take(5)->get(), // Fetch last 5 products
        ];
    }
}
