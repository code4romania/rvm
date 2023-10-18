<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProfileResource\RelationManagers;

use App\Filament\Resources\OrganisationResource\RelationManagers\DocumentsRelationManager as RelationManager;
use Illuminate\Database\Eloquent\Builder;

class DocumentsRelationManager extends RelationManager
{
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation:id,name,city_id,county_id');
    }
}
