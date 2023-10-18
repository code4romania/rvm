<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProfileResource\Pages;

use App\Filament\Resources\ProfileResource;
use App\Filament\Resources\ProfileResource\Concerns\ResolvesRecord;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProfile extends ViewRecord
{
    use ResolvesRecord;

    protected static string $resource = ProfileResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->url(static::getResource()::getUrl('edit')),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    public function getFormTabLabel(): ?string
    {
        return __('organisation.section.profile');
    }
}
