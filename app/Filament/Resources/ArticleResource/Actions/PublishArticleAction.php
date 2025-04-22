<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use App\Models\Article;
use Filament\Pages\Actions\Action;

class PublishArticleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish_article';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('success');

        $this->action(function (Article $record, Action $action) {
            $record->publish();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('article.action.change_status.publish.button'));

        $this->modalHeading(__('article.action.change_status.publish.heading'));
        $this->modalSubheading(__('article.action.change_status.publish.subheading'));
        $this->modalButton(__('article.action.change_status.publish.button'));

        $this->successNotificationTitle(__('article.action.change_status.publish.success'));
    }
}
