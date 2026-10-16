<?php

declare(strict_types=1);

namespace App\Filament\Resources\Profiles;

use App\Filament\Resources\Organisations\OrganisationResource;
use App\Filament\Resources\Profiles\Pages\EditProfile;
use App\Filament\Resources\Profiles\Pages\ViewProfile;
use App\Models\Organisation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

class ProfileResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static ?string $slug = 'profile';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('organisation.label.profile');
    }

    public static function getPluralModelLabel(): string
    {
        return self::getModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return OrganisationResource::form($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ViewProfile::route('/'),
            'edit' => EditProfile::route('/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isOrgAdmin();
    }
}
