<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\CoffeeResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\SizeResource\RelationManagers\SizesRelationManager;
use App\Models\Coffee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoffeeResource extends Resource
{
    protected static ?string $model = Coffee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('coffees')
                    ->visibility('public'),
                Forms\Components\TextInput::make('topics')
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->required()
                    ->default('active'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('sorder')
                    ->rules(['required', 'min:0'])
                    ->width(100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
            ])->defaultSort('sorder', 'asc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All records')
                    ->trueLabel('Active')
                    ->falseLabel('Not active')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_active', 1),
                        false: fn (Builder $query) => $query->where('is_active', '!=', 1),
                        blank: fn (Builder $query) => $query,
                    ),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Coffee $record) {
                        if ($record->img) {
                            Storage::disk('public')->delete($record->img);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(
                            fn (): bool => Auth::user()->role != Role::ADMIN->value
                        )
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if ($record->img) {
                                    Storage::disk('public')->delete($record->img);
                                }
                            }
                        })
                        ->before(function ($records, Tables\Actions\DeleteBulkAction $action) {
                            $not_authorized = ! in_array(Role::from(Auth::user()->role), [Role::ADMIN]);
                            if ($not_authorized) {
                                Notification::make()
                                    ->title(__('general.notification_error.title'))
                                    ->body(__('general.delete_coffee_error'))
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
            SizesRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoffees::route('/'),
            'create' => Pages\CreateCoffee::route('/create'),
            'view' => Pages\ViewCoffee::route('/{record}'),
            'edit' => Pages\EditCoffee::route('/{record}/edit'),
        ];
    }
}
