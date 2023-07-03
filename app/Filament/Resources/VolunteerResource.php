<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Forms\Components\Location;
use App\Filament\Resources\VolunteerResource\Pages;
use App\Models\Volunteer;
use App\Rules\ValidCNP;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getModelLabel(): string
    {
        return __('volunteer.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('volunteer.label.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns()
                    ->schema([
                        Group::make()
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                Select::make('organisation_id')
                                    ->label(__('volunteer.field.organisation'))
                                    ->relationship('organisation', 'name')
                                    ->required(),
                            ]),

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

                        Toggle::make('has_first_aid_accreditation')
                            ->label(__('volunteer.field.has_first_aid_accreditation'))
                            ->columnSpanFull(),

                        Location::make()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('organisation.name')
                    ->label(__('volunteer.field.organisation'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('full_name')
                    ->label(__('volunteer.field.name'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('volunteer.field.email'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label(__('volunteer.field.phone'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('specializations')
                    ->label(__('volunteer.field.specializations'))
                    ->formatStateUsing(
                        static fn ($state): ?string => collect($state)
                            ->map(fn (VolunteerSpecialization $specialization) => $specialization->label())
                            ->filter()
                            ->join(', ')
                    )
                    ->toggleable(),

                IconColumn::make('has_first_aid_accreditation')
                    ->label(__('volunteer.field.has_first_aid_accreditation'))
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('organisation')
                    ->multiple()
                    ->label(__('volunteer.field.organisation'))
                    ->relationship('organisation', 'name'),

                SelectFilter::make('county')
                    ->multiple()
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }
}
