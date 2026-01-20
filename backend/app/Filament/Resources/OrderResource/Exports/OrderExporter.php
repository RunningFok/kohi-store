<?php

namespace App\Filament\Resources\OrderResource\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['customer', 'orderItems']);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Order ID'),
            ExportColumn::make('customer.name')
                ->label('Customer Name'),
            ExportColumn::make('customer.email')
                ->label('Customer Email'),
            ExportColumn::make('customer.phone')
                ->label('Customer Phone'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('total_amount')
                ->label('Total Amount')
                ->formatStateUsing(fn ($state) => '€' . number_format($state, 2, '.', '')),
            ExportColumn::make('total_quantity')
                ->label('Total Items')
                ->state(function (Order $record): int {
                    return $record->orderItems()->sum('quantity');
                }),
            ExportColumn::make('shipping_address')
                ->label('Shipping Address'),
            ExportColumn::make('shipping_city')
                ->label('Shipping City'),
            ExportColumn::make('shipping_postal_code')
                ->label('Shipping Postal Code'),
            ExportColumn::make('shipping_country')
                ->label('Shipping Country'),
            ExportColumn::make('created_at')
                ->label('Order Date')
                ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return __('filament-actions::export.notifications.completed.body', [
            'count' => $export->successful_rows,
        ]);
    }

    public function getFileName(Export $export): string
    {
        return 'orders-' . now()->format('Y-m-d_His');
    }
}
