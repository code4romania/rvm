<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('view', $this->getRecord());
    }

    protected function getSubheading(): string|Htmlable|null
    {
        return new HtmlString('<b>Important:</b> Știrile publicate în această secțiune trebuie să aibă legătură directă cu situații de urgență, protecție civilă sau alte subiecte relevante pentru colaborarea cu Departamentul pentru Situații de Urgență.');
    }
}
