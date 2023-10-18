<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProfileResource\RelationManagers;

use App\Filament\Resources\OrganisationResource\RelationManagers\ResourcesRelationManager as RelationManager;
use Illuminate\Database\Eloquent\Builder;

class ResourcesRelationManager extends RelationManager
{
    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()->with('organisation:id,name,city_id,county_id');
    // }
}
