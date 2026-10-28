<?php

declare(strict_types=1);

namespace App\Filament\Infolists\EntryGroups;

use App\Filament\Infolists\Components\LocationEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class TvEntryGroup extends EntryGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Section::make(__('resource.fields.attributes'))
                ->columns(2)
                ->schema([
                    TextEntry::make('properties.coverage')
                        ->label(__('resource.attributes.coverage.label'))
                        ->columnSpanFull(),

                ]),

            Section::make(__('resource.fields.localisation'))
                ->schema([
                    LocationEntry::make()
                        ->withoutCity(),
                ]),
        ]);
    }
}
