<?php

declare(strict_types=1);

namespace App\Filament\Resources\Volunteers;

use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\Resources\Schemas\ResourceForm;
use App\Filament\Resources\Volunteers\Pages\CreateVolunteer;
use App\Filament\Resources\Volunteers\Pages\EditVolunteer;
use App\Filament\Resources\Volunteers\Pages\ListVolunteers;
use App\Filament\Resources\Volunteers\Pages\ViewVolunteer;
use App\Filament\Resources\Volunteers\Schemas\VolunteerInfolist;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\Volunteer;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('volunteer.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('volunteer.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return ResourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VolunteerInfolist::configure($schema);
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
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
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
                    ->hidden(fn () => auth()->user()->belongsToOrganisation())
                    ->relationship('organisation', 'name'),

                SelectFilter::make('county')
                    ->multiple()
                    ->label(__('general.county'))
                    ->relationship('county', 'name'),

                SelectFilter::make('specializations')
                    ->multiple()
                    ->label(__('volunteer.field.specializations'))
                    ->options(VolunteerSpecialization::options())
                    ->query(
                        fn (Builder $query, array $state) => collect($state['values'])
                            ->map(fn ($value) => $query->whereJsonContains('specializations', $value))
                    ),

                TernaryFilter::make('has_first_aid_accreditation')
                    ->label(__('volunteer.field.has_first_aid_accreditation')),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => ListVolunteers::route('/'),
            'create' => CreateVolunteer::route('/create'),
            'view' => ViewVolunteer::route('/{record}'),
            'edit' => EditVolunteer::route('/{record}/edit'),
        ];
    }
}
