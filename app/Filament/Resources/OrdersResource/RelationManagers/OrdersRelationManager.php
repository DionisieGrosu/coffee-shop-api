<?php

namespace App\Filament\Resources\OrdersResource\RelationManagers;

use App\Enums\Order as EnumsOrder;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order.name')
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record]))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record]))
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->url(fn ($record): string => OrderResource::getUrl('edit', ['record' => $record]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options(EnumsOrder::to_array()),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([

            ]);
    }
}
