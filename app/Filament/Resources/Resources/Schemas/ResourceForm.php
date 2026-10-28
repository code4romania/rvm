<?php

declare(strict_types=1);

namespace App\Filament\Resources\Resources\Schemas;

use App\Filament\Forms\FieldGroups\AircraftFieldGroup;
use App\Filament\Forms\FieldGroups\BoatFieldGroup;
use App\Filament\Forms\FieldGroups\BroadcastFieldGroup;
use App\Filament\Forms\FieldGroups\RadioFieldGroup;
use App\Filament\Forms\FieldGroups\RescueDogFieldGroup;
use App\Filament\Forms\FieldGroups\TentFieldGroup;
use App\Filament\Forms\FieldGroups\TrailerFieldGroup;
use App\Filament\Forms\FieldGroups\TvFieldGroup;
use App\Filament\Forms\FieldGroups\VehicleFieldGroup;
use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ResourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('resource.fields.name'))
                            ->maxLength(255)
                            ->required(),

                        Select::make('organisation_id')
                            ->relationship('organisation', 'name')
                            ->label(__('resource.fields.organisation'))
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'name', fn (Builder $query) => $query->orderBy('id'))
                            ->label(__('resource.fields.category'))
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (callable $set) {
                                $set('subcategory_id', null);
                                $set('types', null);
                                $set('properties', []);
                            }),

                        Select::make('subcategory_id')
                            ->label(__('resource.fields.subcategory'))
                            ->options(
                                function (callable $get, callable $set) {
                                    if (! $get('category_id')) {
                                        return;
                                    }

                                    return Subcategory::query()
                                        ->inCategory($get('category_id'))
                                        ->pluck('name', 'id');
                                }
                            )
                            ->disabled(fn (callable $get) => $get('category_id') === null)
                            ->live()
                            ->required(),
                    ]),

                AircraftFieldGroup::make(),
                BoatFieldGroup::make(),
                BroadcastFieldGroup::make(),
                RadioFieldGroup::make(),
                RescueDogFieldGroup::make(),
                TentFieldGroup::make(),
                TrailerFieldGroup::make(),
                TvFieldGroup::make(),
                VehicleFieldGroup::make(),

                Section::make(__('resource.fields.contact'))
                    ->columns()
                    ->schema([
                        TextInput::make('contact_name')
                            ->label(__('resource.fields.contact'))
                            ->required(),

                        TextInput::make('contact_phone')
                            ->label(__('resource.fields.contact_phone'))
                            ->tel()
                            ->required(),
                    ]),

                Section::make()
                    ->heading('')
                    ->schema([
                        Textarea::make('comments')
                            ->label(__('resource.fields.comments'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
