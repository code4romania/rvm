<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProfileResource\Pages;

use App\Filament\Resources\ProfileResource;
use App\Filament\Resources\ProfileResource\Concerns\ResolvesRecord;
use Filament\Resources\Pages\EditRecord;

class EditProfile extends EditRecord
{
    use ResolvesRecord;

    protected static string $resource = ProfileResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getRelationManagers(): array
    {
        return [
            //
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    public function getFormTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
