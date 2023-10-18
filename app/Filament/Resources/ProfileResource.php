<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileResource\Pages;
use App\Filament\Resources\ProfileResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\ProfileResource\RelationManagers\ResourcesRelationManager;
use App\Filament\Resources\ProfileResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\ProfileResource\RelationManagers\VolunteersRelationManager;
use App\Models\Organisation;
use Filament\Resources\Form;
use Filament\Resources\Resource;

class ProfileResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static ?string $slug = 'profile';

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('organisation.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return self::getModelLabel();
    }

    public static function form(Form $form): Form
    {
        return OrganisationResource::form($form);
    }

    public static function getRelations(): array
    {
        return [
            VolunteersRelationManager::class,
            ResourcesRelationManager::class,
            UsersRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewProfile::route('/'),
            'edit' => Pages\EditProfile::route('/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isOrgAdmin();
    }
}
