<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Actions;

use App\Models\News;
use Filament\Pages\Actions\Action;

class PublishNewsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish_news';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('success');

        $this->action(function (News $record, Action $action) {
            $record->publish();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('news.action.change_status.publish.button'));

        $this->modalHeading(__('news.action.change_status.publish.heading'));
        $this->modalSubheading(__('news.action.change_status.publish.subheading'));
        $this->modalButton(__('news.action.change_status.publish.button'));

        $this->successNotificationTitle(__('news.action.change_status.publish.success'));
    }
}
