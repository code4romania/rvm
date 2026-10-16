<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\RelationManagers;

use App\Enum\UserRole;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Tables\Actions\ExportAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getResource(): string
    {
        return UserResource::class;
    }

    protected static function getModelLabel(): ?string
    {
        return static::getResource()::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return static::getResource()::getPluralModelLabel();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->unique(ignoreRecord: true)
                    ->maxLength(200)
                    ->email()
                    ->required(),

                TextInput::make('phone')
                    ->label(__('user.field.phone'))
                    ->maxLength(14)
                    ->tel()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

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
                    ->label(__('user.field.email'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make(),

                CreateAction::make()
                    ->mutateDataUsing(function (array $data) {
                        $data['role'] = UserRole::ORG_ADMIN;

                        return $data;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
