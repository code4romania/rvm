<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Actions;

use App\Models\News;
use Filament\Actions\Action;

class ArchiveNewsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'archive_news';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('warning');

        $this->action(function (News $record, Action $action) {
            $record->archive();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('news.action.change_status.archive.button'));

        $this->modalHeading(__('news.action.change_status.archive.heading'));
        $this->modalDescription(__('news.action.change_status.archive.subheading'));
        $this->modalSubmitActionLabel(__('news.action.change_status.archive.button'));

        $this->successNotificationTitle(__('news.action.change_status.archive.success'));
    }
}
