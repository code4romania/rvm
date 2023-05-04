<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Filament\Resources\VolunteerResource;
use App\Models\Volunteer;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class VolunteersRelationManager extends RelationManager
{
    protected static string $relationship = 'volunteers';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return VolunteerResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Volunteer::getTableColumns())
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('organisation.modal.heading'))
                    ->modalSubheading(__('organisation.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
