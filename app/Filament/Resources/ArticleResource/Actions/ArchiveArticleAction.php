<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use App\Models\Article;
use Filament\Pages\Actions\Action;

class ArchiveArticleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'archive_article';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('warning');

        $this->action(function (Article $record, Action $action) {
            $record->archive();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('article.action.change_status.archive.button'));

        $this->modalHeading(__('article.action.change_status.archive.heading'));
        $this->modalSubheading(__('article.action.change_status.archive.subheading'));
        $this->modalButton(__('article.action.change_status.archive.button'));

        $this->successNotificationTitle(__('article.action.change_status.archive.success'));
    }
}
