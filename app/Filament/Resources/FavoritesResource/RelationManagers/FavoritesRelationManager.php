<?php

namespace App\Filament\Resources\FavoritesResource\RelationManagers;

use App\Enums\Role;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class FavoritesRelationManager extends RelationManager
{
    protected static string $relationship = 'favorites';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('coffee_id')
                    ->relationship('coffee', 'name')
                    ->unique('favorites', 'coffee_id', function (RelationManager $livewire, $get) {
                        $check_if_exists = Favorite::where('user_id', '!=', $livewire->getOwnerRecord()->id)->where('coffee_id', $get('coffee_id'))->first();

                        return $check_if_exists;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('coffee.name')
            ->columns([
                Tables\Columns\TextColumn::make('coffee.name'),
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
                                    ->body(__('general.delete_favorite_error'))
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
