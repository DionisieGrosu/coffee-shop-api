<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use App\Enums\Role;
use App\Models\Size;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SizesRelationManager extends RelationManager
{
    protected static string $relationship = 'sizes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default('active'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextInputColumn::make('sorder')
                    ->rules(['required', 'min:0'])
                    ->width(100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price $'),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\ToggleColumn::make('default')
                    ->beforeStateUpdated(function () {
                        Size::where('coffee_id', $this->getOwnerRecord()->id)->update(['default' => 0]);
                    }),
            ])->defaultSort('sorder', 'asc')
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
                                    ->body(__('general.delete_size_error'))
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
