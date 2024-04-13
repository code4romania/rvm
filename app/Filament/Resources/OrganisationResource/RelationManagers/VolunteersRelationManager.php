<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Forms\Components\Location;
use App\Filament\Resources\VolunteerResource;
use App\Filament\Tables\Actions\ExportAction;
use App\Rules\ValidCNP;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class VolunteersRelationManager extends RelationManager
{
    protected static string $relationship = 'volunteers';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getResource(): string
    {
        return VolunteerResource::class;
    }

    protected static function getModelLabel(): string
    {
        return static::getResource()::getModelLabel();
    }

    protected static function getPluralModelLabel(): string
    {
        return static::getResource()::getPluralModelLabel();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->unique()
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
                    ->reactive(),

                TextInput::make('language')
                    ->label(__('volunteer.field.language'))
                    ->required()
                    ->visible(
                        fn (callable $get) => \in_array('translator', $get('specializations'))
                    ),

                Location::make()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
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
                    ->label(__('volunteer.field.specializations'))
                    ->formatStateUsing(
                        static fn ($state): ?string => collect($state)
                            ->map(fn (VolunteerSpecialization $specialization) => $specialization->label())
                            ->filter()
                            ->join(', ')
                    ),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make(),
//                VolunteerResource\Actions\ImportVolunteersAction::make(),
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('volunteer.modal.heading'))
                    ->modalSubheading(__('volunteer.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
