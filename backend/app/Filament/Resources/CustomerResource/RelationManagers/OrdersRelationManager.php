<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->infolist([
                        Infolists\Components\Section::make('Order Information')
                            ->schema([
                                Infolists\Components\TextEntry::make('id')
                                    ->label('Order ID'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('total_amount')
                                    ->label('Total Amount')
                                    ->money('EUR'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Order Date')
                                    ->dateTime(),
                            ])
                            ->columns(2),

                        Infolists\Components\Section::make('Order Items')
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('orderItems')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('product.name')
                                            ->label('Product')
                                            ->weight('bold'),
                                        Infolists\Components\TextEntry::make('quantity')
                                            ->badge()
                                            ->color('info'),
                                        Infolists\Components\TextEntry::make('price')
                                            ->label('Price per Unit')
                                            ->money('EUR'),
                                        Infolists\Components\TextEntry::make('subtotal')
                                            ->label('Subtotal')
                                            ->money('EUR')
                                            ->weight('bold'),
                                    ])
                                    ->columns(4),
                            ]),
                    ]),
                ]);
                
    }
}
