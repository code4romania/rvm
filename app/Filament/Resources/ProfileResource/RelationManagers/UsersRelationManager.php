<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProfileResource\RelationManagers;

use App\Filament\Resources\OrganisationResource\RelationManagers\UsersRelationManager as RelationManager;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation:id,name,city_id,county_id');
    }
}
