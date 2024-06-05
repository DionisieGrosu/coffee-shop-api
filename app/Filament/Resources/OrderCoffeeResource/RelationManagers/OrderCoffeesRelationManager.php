<?php

namespace App\Filament\Resources\OrderCoffeeResource\RelationManagers;

use App\Enums\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderCoffeesRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderCoffee';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('coffee_id')
                    ->relationship('coffee', 'name'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $total = $get('price') * $get('qt');
                        $set('total_price', $total);
                    }),
                Forms\Components\TextInput::make('qt')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $total = $get('price') * $get('qt');
                        $set('total_price', $total);
                    }),
                Forms\Components\TextInput::make('total_price')
                    ->placeholder(function (Get $get, Set $set) {
                        $total = $get('price') * $get('qt');
                        $set('total_price', $total);

                        return $total;
                    })
                    ->numeric()
                    ->readOnly()
                    ->prefix('$')
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('coffee.name')
            ->columns([
                Tables\Columns\TextColumn::make('coffee.name'),
                Tables\Columns\TextColumn::make('price')->money('usd'),
                Tables\Columns\TextColumn::make('qt'),
                Tables\Columns\TextColumn::make('total_price')
                    ->getStateUsing(function (Model $record) {
                        $total = $record->price * $record->qt;

                        return $total;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(
                            fn (): bool => Auth::user()->role != Role::ADMIN->value
                        )
                        ->before(function ($records, Tables\Actions\DeleteBulkAction $action) {
                            $not_authorized = ! in_array(Role::from(Auth::user()->role), [Role::ADMIN]);
                            if ($not_authorized) {
                                Notification::make()
                                    ->title(__('general.notification_error.title'))
                                    ->body(__('general.delete_OrderCoffee_error'))
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
