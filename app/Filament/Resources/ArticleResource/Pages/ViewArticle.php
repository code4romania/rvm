<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\EditAction;
use Filament\Pages\Actions\DeleteAction;
use App\Models\Article;
use App\Filament\Resources\ArticleResource\Actions\PublishArticleAction;
use App\Filament\Resources\ArticleResource\Actions\DraftArticleAction;
use App\Filament\Resources\ArticleResource\Actions\ArchiveArticleAction;

class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->title;
    }

    protected function getActions(): array
    {
        return [
            PublishArticleAction::make()
                ->hidden(fn(Article $record) => $record->isPublished())
                ->record($this->getRecord()),

            DraftArticleAction::make()
                ->hidden(fn(Article $record) => $record->isDrafted())
                ->record($this->getRecord()),

            ArchiveArticleAction::make()
                ->hidden(fn(Article $record) => $record->isArchived())
                ->record($this->getRecord()),

            EditAction::make(),

            DeleteAction::make(),
        ];
    }
}
