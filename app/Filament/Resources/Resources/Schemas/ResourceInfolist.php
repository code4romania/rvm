<?php

declare(strict_types=1);

namespace App\Filament\Resources\Resources\Schemas;

use App\Filament\Infolists\EntryGroups\AircraftEntryGroup;
use App\Filament\Infolists\EntryGroups\BoatEntryGroup;
use App\Filament\Infolists\EntryGroups\BroadcastEntryGroup;
use App\Filament\Infolists\EntryGroups\RadioEntryGroup;
use App\Filament\Infolists\EntryGroups\RescueDogEntryGroup;
use App\Filament\Infolists\EntryGroups\TentEntryGroup;
use App\Filament\Infolists\EntryGroups\TrailerEntryGroup;
use App\Filament\Infolists\EntryGroups\TvEntryGroup;
use App\Filament\Infolists\EntryGroups\VehicleEntryGroup;
use App\Filament\Resources\Organisations\RelationManagers\ResourcesRelationManager;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ResourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('resource.fields.name')),

                        TextEntry::make('organisation.name')
                            ->label(__('resource.fields.organisation'))
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->hiddenOn(ResourcesRelationManager::class),

                        TextEntry::make('category.name')
                            ->label(__('resource.fields.category'))
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('subcategory_id', null);
                                $set('types', null);
                                $set('properties', []);
                            }),

                        TextEntry::make('subcategory.name')
                            ->label(__('resource.fields.subcategory')),

                        TextEntry::make('subcategory.id')
                            ->label(__('resource.fields.subcategory'))
                            ->hidden()
                            ->live(),
                    ]),

                AircraftEntryGroup::make(),
                BoatEntryGroup::make(),
                BroadcastEntryGroup::make(),
                RadioEntryGroup::make(),
                RescueDogEntryGroup::make(),
                TentEntryGroup::make(),
                TrailerEntryGroup::make(),
                TvEntryGroup::make(),
                VehicleEntryGroup::make(),

                Section::make(__('resource.fields.contact'))
                    ->columns()
                    ->schema([
                        TextEntry::make('contact_name')
                            ->label(__('resource.fields.contact')),

                        TextEntry::make('contact_phone')
                            ->label(__('resource.fields.contact_phone')),
                    ]),

                Section::make()
                    ->schema([
                        TextEntry::make('comments')
                            ->label(__('resource.fields.comments'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
