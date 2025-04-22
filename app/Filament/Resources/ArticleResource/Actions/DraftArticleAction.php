<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use App\Models\Article;
use Filament\Pages\Actions\Action;

class DraftArticleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'draft_article';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->color('secondary');

        $this->action(function (Article $record, Action $action) {
            $record->draft();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('article.action.change_status.draft.button'));

        $this->modalHeading(__('article.action.change_status.draft.heading'));
        $this->modalSubheading(__('article.action.change_status.draft.subheading'));
        $this->modalButton(__('article.action.change_status.draft.button'));

        $this->successNotificationTitle(__('article.action.change_status.draft.success'));
    }
}
