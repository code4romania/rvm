<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Enum\OrganisationType;
use App\Filament\Resources\OrganisationResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganisations extends ListRecords
{
    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading(__('organisation.modal.heading'))
                ->modalSubheading(__('organisation.modal.subheading'))
                ->slideOver()
                ->form([
                    TextInput::make('name')
                        ->label(__('organisation.field.name'))
                        ->placeholder(__('organisation.placeholder.name'))
                        ->maxLength(200)
                        ->required()
                        ->inlineLabel(),

                    TextInput::make('alias')
                        ->label(__('organisation.field.alias'))
                        ->placeholder(__('organisation.placeholder.alias'))
                        ->maxLength(200)
                        ->nullable()
                        ->inlineLabel(),

                    TextInput::make('email')
                        ->label(__('organisation.field.email_organisation'))
                        ->placeholder(__('organisation.placeholder.email'))
                        ->email()
                        ->required()
                        ->inlineLabel(),

                    TextInput::make('phone')
                        ->label(__('organisation.field.phone_organisation'))
                        ->placeholder(__('organisation.placeholder.phone'))
                        ->tel()
                        ->required()
                        ->inlineLabel(),

                    Select::make('type')
                        ->label(__('organisation.field.type'))
                        ->required()
                        ->options(OrganisationType::options())
                        ->enum(OrganisationType::class)
                        ->inlineLabel(),
                ]),
        ];
    }
}
