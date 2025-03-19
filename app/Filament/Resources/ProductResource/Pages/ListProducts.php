<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            TextColumn::make('name')
                ->label('Product Name')
                ->searchable()
                ->sortable(),

            TextColumn::make('barcode')
                ->label('Barcode')
                ->icon('heroicon-o-qrcode')
                ->tooltip('Product barcode')
                ->searchable()
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            // ✅ Time-Based Filters
            Filter::make('time_range')
                ->label('Filter by Time')
                ->form([
                    Select::make('time_range')
                        ->options([
                            'hour' => 'Last 1 Hour',
                            '6hours' => 'Last 6 Hours',
                            'day' => 'Last 24 Hours',
                            'week' => 'Last 7 Days',
                            'month' => 'Last 30 Days',
                            'year' => 'Last 1 Year',
                            'all' => 'Show All',
                        ])
                        ->default('month'),
                ])
                ->query(fn (Builder $query, array $data) => 
                    $query->when($data['time_range'] ?? 'month', function ($q, $timeRange) {
                        $timeMapping = [
                            'hour' => Carbon::now()->subHour(),
                            '6hours' => Carbon::now()->subHours(6),
                            'day' => Carbon::now()->subDay(),
                            'week' => Carbon::now()->subWeek(),
                            'month' => Carbon::now()->subMonth(),
                            'year' => Carbon::now()->subYear(),
                        ];

                        if ($timeRange !== 'all') {
                            return $q->where('created_at', '>=', $timeMapping[$timeRange]);
                        }
                        return $q;
                    })
                ),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
