<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\FavoritesResource\RelationManagers\FavoritesRelationManager;
use App\Filament\Resources\OrdersResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->hidden(
                        fn (): bool => Auth::user()->role != Role::ADMIN->value
                    )
                    ->options([
                        Role::ADMIN->value => Role::ADMIN->value,
                        Role::CLIENT->value => Role::CLIENT->value,
                        Role::USER->value => Role::USER->value,
                    ]),
                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('users')
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('role'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(Role::to_array()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                            $ids = $records->pluck('id')->toArray();
                            $exists = User::whereIn('id', $ids)->where('email', 'admin@gmail.com')->exists();
                            $not_authorized = ! in_array(Role::from(Auth::user()->role), [Role::ADMIN]);
                            if ($not_authorized || $exists) {
                                Notification::make()
                                    ->title(__('general.notification_error.title'))
                                    ->body(__('general.delete_user_error'))
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FavoritesRelationManager::class,
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
