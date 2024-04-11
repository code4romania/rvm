<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Filament\Resources\VolunteerResource\Actions\ExportVolunteersExample;
use App\Filament\Resources\VolunteerResource\Actions\ImportVolunteersAction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVolunteers extends ListRecords
{
    protected static string $resource = VolunteerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportVolunteersAction::make(),
//            ExportVolunteersExample::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation');
    }
}
