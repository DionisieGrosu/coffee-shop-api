<?php

namespace App\Filament\Resources\ReviewResource\RelationManagers;

use App\Enums\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rate')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('rate')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rate'),
                Tables\Columns\TextColumn::make('user.name'),
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
                                    ->body(__('general.delete_review_error'))
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
