<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Actions;

use App\Models\News;
use Filament\Pages\Actions\Action;

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
        $this->modalSubheading(__('news.action.change_status.archive.subheading'));
        $this->modalButton(__('news.action.change_status.archive.button'));

        $this->successNotificationTitle(__('news.action.change_status.archive.success'));
    }
}
