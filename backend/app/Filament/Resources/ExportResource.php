<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportResource\Pages;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Exports';

    protected static ?int $navigationSort = 10;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('exporter')
                    ->label('Exporter')
                    ->formatStateUsing(fn (string $state) => class_basename($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('File Name')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Pending'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'pending' => $query->whereNull('completed_at')->where('processed_rows', 0),
                            'processing' => $query->whereNull('completed_at')->where('processed_rows', '>', 0),
                            'completed' => $query->whereNotNull('completed_at'),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download_csv')
                    ->label('Download CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn (Export $record) => $record->completed_at !== null && $record->file_name !== null)
                    ->url(fn (Export $record) => route('filament.exports.download', $record) . '?format=csv')
                    ->openUrlInNewTab(),
                ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExports::route('/'),
        ];
    }
}
