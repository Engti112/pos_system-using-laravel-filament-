<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('Cashier')
                ->relationship('user', 'name')
                ->searchable()
                ->default(Auth::id())
                ->disabled()
                ->required(),

            TextInput::make('discount')
                ->numeric()
                ->default(0)
                ->label('Discount')
                ->required(),

            Select::make('payment_method')
                ->label('Payment Method')
                ->options([
                    'cash' => 'Cash',
                    'card' => 'Card',
                ])
                ->required(),

            Section::make('Sale Items')
                ->schema([
                    Repeater::make('saleItems')
                        ->relationship('saleItems')
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(Product::pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) => $set('stock_entry_id', null))
                                ->afterStateUpdated(fn ($state, callable $set) => $set('purchase_price', StockEntry::where('product_id', $state)->latest()->value('purchase_price')))
                                ->afterStateUpdated(fn ($state, callable $set) => $set('selling_price', StockEntry::where('product_id', $state)->latest()->value('selling_price'))),

                            Select::make('stock_entry_id')
                                ->label('Stock Entry')
                                ->options(fn (callable $get) => StockEntry::where('product_id', $get('product_id'))->pluck('id', 'id'))
                                ->searchable()
                                ->required(),

                            TextInput::make('quantity')
                                ->numeric()
                                ->minValue(1)
                                ->label('Quantity')
                                ->required(),

                            TextInput::make('purchase_price')
                                ->numeric()
                                ->label('Purchase Price')
                                ->required(),

                            TextInput::make('selling_price')
                                ->numeric()
                                ->label('Selling Price')
                                ->required(),
                        ])
                        ->minItems(1)
                        ->addActionLabel('Add Sale Item')
                ]),
        ]);
    }
    public static function query(Builder $query): Builder
    {
        return $query->with(['saleItems.stockEntry.product']);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->label('Cashier')->sortable(),
                TextColumn::make('total_amount')->label('Total Amount')->money('INR')->sortable(),
                TextColumn::make('discount')->label('Discount')->money('INR')->sortable(),
                TextColumn::make('final_amount')->label('Final Amount')->money('INR')->sortable(),
                TextColumn::make('payment_method')->label('Payment Method')->sortable(),
                TextColumn::make('created_at')->label('Sale Date')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')->label('Filter by Cashier')->relationship('user', 'name')->searchable(),
                SelectFilter::make('payment_method')->label('Filter by Payment Method')->options([
                    'cash' => 'Cash',
                    'card' => 'Card',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
            'view' => Pages\ViewSale::route('/{record}'),
        ];
    }
}
