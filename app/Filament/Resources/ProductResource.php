<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('barcode')
                    ->label('Barcode')
                    ->unique(Product::class) // Fix: Ensure uniqueness check on correct model
                    ->required()
                    ->maxLength(50),

                Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('barcode')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Created At')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn ($query, array $data) => 
                        $query->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                              ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']))
                    ),

                Filter::make('barcode')
                    ->label('Barcode')
                    ->form([
                        TextInput::make('barcode')->placeholder('Enter barcode'),
                    ])
                    ->query(fn ($query, array $data) => 
                        $query->when($data['barcode'], fn ($q) => $q->where('barcode', $data['barcode']))
                    ),

                Filter::make('recently_added')
                    ->label('Recently Added')
                    ->query(fn (Builder $query) => 
                        $query->where('created_at', '>=', now()->subDays(30))
                    ),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
