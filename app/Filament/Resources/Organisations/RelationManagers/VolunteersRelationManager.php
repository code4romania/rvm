<?php

declare(strict_types=1);

namespace App\Filament\Resources\Organisations\RelationManagers;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Forms\Components\Location;
use App\Filament\Resources\Volunteers\VolunteerResource;
use App\Filament\Tables\Actions\ExportAction;
use App\Rules\ValidCNP;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VolunteersRelationManager extends RelationManager
{
    protected static string $relationship = 'volunteers';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getResource(): string
    {
        return VolunteerResource::class;
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
                TextInput::make('last_name')
                    ->label(__('volunteer.field.last_name'))
                    ->maxLength(200)
                    ->required(),

                TextInput::make('first_name')
                    ->label(__('volunteer.field.first_name'))
                    ->maxLength(200)
                    ->required(),

                TextInput::make('email')
                    ->label(__('volunteer.field.email'))
                    ->maxLength(200)
                    ->email()
                    ->required(),

                TextInput::make('phone')
                    ->label(__('volunteer.field.phone'))
                    ->maxLength(14)
                    ->tel(),

                TextInput::make('cnp')
                    ->label(__('volunteer.field.cnp'))
                    ->rule(new ValidCNP)
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('role')
                    ->label(__('volunteer.field.role'))
                    ->options(VolunteerRole::options())
                    ->enum(VolunteerRole::class)
                    ->required(),

                Select::make('specializations')
                    ->label(__('volunteer.field.specializations'))
                    ->options(VolunteerSpecialization::options())
                    ->multiple()
                    ->required()
                    ->live(),

                TextInput::make('language')
                    ->label(__('volunteer.field.language'))
                    ->maxLength(200)
                    ->visible(
                        fn (Get $get) => \is_array($get('specializations')) && \in_array('translator', $get('specializations'))
                    ),

                Location::make()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('full_name')
                    ->label(__('volunteer.field.name'))
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('volunteer.field.email'))
                    ->sortable(),

                TextColumn::make('phone')
                    ->label(__('volunteer.field.phone'))
                    ->sortable(),

                TextColumn::make('specializations')
                    ->label(__('volunteer.field.specializations')),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make(),

                CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('volunteer.modal.heading'))
                    ->modalDescription(__('volunteer.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
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
