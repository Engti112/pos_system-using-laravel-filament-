<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockEntryResource\Pages;
use App\Models\Product;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class StockEntryResource extends Resource
{
    protected static ?string $model = StockEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Inventory Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Product')
                ->options(
                    Product::all()->pluck('name', 'id')->map(function ($name, $id) {
                        $product = Product::find($id);
                        return "{$name} - {$product->description}";
                    })
                )
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => 
                    $set('purchase_price', StockEntry::where('product_id', $state)->latest()->value('purchase_price') ?? null)
                ),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->placeholder('Enter quantity')
                ->required()
                ->autocomplete(false)
                ->datalist(
                    StockEntry::orderBy('created_at', 'desc')->pluck('quantity')->toArray()
                ),

            TextInput::make('purchase_price')
                ->numeric()
                ->required(),

            TextInput::make('selling_price')
                ->numeric()
                ->required(),

            DatePicker::make('expiry_date')
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('product.name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('purchase_price')
                    ->label('Purchase Price')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Stock Entry Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Entry Date Range')
                    ->form([
                        DatePicker::make('from')->label('From Date')->displayFormat('Y-m-d'),
                        DatePicker::make('until')->label('Until Date')->displayFormat('Y-m-d'),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        $query->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                              ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']))
                    ),

                Filter::make('quantity')
                    ->label('Quantity Range')
                    ->form([
                        TextInput::make('min_quantity')->numeric()->label('Min Quantity'),
                        TextInput::make('max_quantity')->numeric()->label('Max Quantity'),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        $query->when($data['min_quantity'], fn ($q) => $q->where('quantity', '>=', $data['min_quantity']))
                              ->when($data['max_quantity'], fn ($q) => $q->where('quantity', '<=', $data['max_quantity']))
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockEntries::route('/'),
            'create' => Pages\CreateStockEntry::route('/create'),
            'edit' => Pages\EditStockEntry::route('/{record}/edit'),
        ];
    }
}
