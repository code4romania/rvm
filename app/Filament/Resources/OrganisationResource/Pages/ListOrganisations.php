<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Enum\OrganisationType;
use App\Filament\Resources\OrganisationResource;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Str;

class ListOrganisations extends ListRecords
{
    protected bool $hasModalViewRendered = true;

    protected static string $resource = OrganisationResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('createOrganisation')
                ->action(function (array $data): void {
                    $model = $this->getModel();
                    $model::create($data);
                })
                ->requiresConfirmation()
                ->modalHeading(__('organisation.modal.heading'))
                ->modalSubheading(__('organisation.modal.subheading'))
                ->modalWidth('4xl')
                ->form([
                    TextInput::make('name')
                        ->label(__('organisation.field.name'))
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('alias', Str::slug($state));
                        })
                        ->required()
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hint(__('organisation.field.name'))
                        ->inlineLabel(),
                    TextInput::make('alias')
                        ->label(__('organisation.field.alias'))
                        ->nullable()
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hint(__('organisation.field.alias'))
                        ->inlineLabel(),
                    TextInput::make('email')
                        ->label(__('organisation.field.email_organisation'))
                        ->email()
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hint(__('organisation.field.email'))
                        ->required()
                        ->inlineLabel(),
                    TextInput::make('phone')
                        ->label(__('organisation.field.phone_organisation'))
                        ->required()
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hint(__('organisation.field.phone'))
                        ->inlineLabel(),
                    Select::make('type')
                        ->label(__('organisation.field.type'))
                        ->required()
                        ->options(OrganisationType::options())
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hint(__('organisation.field.intervention_type'))
                        ->inlineLabel(),
                ])->slideOver(),
        ];
    }
}
