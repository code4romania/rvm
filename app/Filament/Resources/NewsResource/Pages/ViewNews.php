<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Filament\Resources\NewsResource\Actions\ArchiveNewsAction;
use App\Filament\Resources\NewsResource\Actions\DraftNewsAction;
use App\Filament\Resources\NewsResource\Actions\PublishNewsAction;
use App\Models\News;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ViewNews extends ViewRecord
{
    protected static string $resource = NewsResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->title;
    }

    protected function getActions(): array
    {
        return [
            PublishNewsAction::make()
                ->hidden(fn (News $record) => $record->isPublished())
                ->record($this->getRecord()),

            DraftNewsAction::make()
                ->hidden(fn (News $record) => $record->isDrafted())
                ->record($this->getRecord()),

            ArchiveNewsAction::make()
                ->hidden(fn (News $record) => $record->isArchived())
                ->record($this->getRecord()),

            EditAction::make(),

            DeleteAction::make(),
        ];
    }

    protected function getSubheading(): Htmlable
    {
        return new HtmlString(__('news.disclaimer'));
    }
}
