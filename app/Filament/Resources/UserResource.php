<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('user.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns()
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('user.field.first_name'))
                            ->maxLength(100)
                            ->required(),

                        TextInput::make('last_name')
                            ->label(__('user.field.last_name'))
                            ->maxLength(100)
                            ->required(),

                        TextInput::make('email')
                            ->label(__('user.field.email'))
                            ->maxLength(200)
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label(__('user.field.phone'))
                            ->maxLength(14)
                            ->tel()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label(__('user.field.first_name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('last_name')
                    ->label(__('user.field.last_name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Tables\Columns\TextColumn::make('role')
                //     ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
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
