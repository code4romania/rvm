<?php

declare(strict_types=1);

namespace App\Filament\Resources\Volunteers\Schemas;

use App\Filament\Infolists\Components\LocationEntry;
use App\Filament\Resources\Organisations\RelationManagers\VolunteersRelationManager;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class VolunteerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns()
                    ->schema([
                        Group::make()
                            ->hidden(fn () => auth()->user()->belongsToOrganisation())
                            ->columns()
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('organisation.name')
                                    ->label(__('volunteer.field.organisation')),
                            ])
                            ->hiddenOn(VolunteersRelationManager::class),

                        TextEntry::make('last_name')
                            ->label(__('volunteer.field.last_name')),

                        TextEntry::make('first_name')
                            ->label(__('volunteer.field.first_name')),

                        TextEntry::make('email')
                            ->label(__('volunteer.field.email')),

                        TextEntry::make('phone')
                            ->label(__('volunteer.field.phone')),

                        TextEntry::make('cnp')
                            ->label(__('volunteer.field.cnp')),

                        TextEntry::make('role')
                            ->label(__('volunteer.field.role')),

                        TextEntry::make('specializations')
                            ->label(__('volunteer.field.specializations'))
                            ->badge(),

                        TextEntry::make('language')
                            ->label(__('volunteer.field.language'))
                            ->visible(
                                fn (Get $get) => collect($get('specializations'))->contains('name', 'translator')
                            ),

                        IconEntry::make('has_first_aid_accreditation')
                            ->label(__('volunteer.field.has_first_aid_accreditation'))
                            ->hiddenOn(VolunteersRelationManager::class)
                            ->columnSpanFull(),

                        LocationEntry::make(),
                    ]),
            ]);
    }
}
