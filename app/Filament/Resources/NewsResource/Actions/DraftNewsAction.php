<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Actions;

use App\Models\News;
use Filament\Pages\Actions\Action;

class DraftNewsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'draft_news';
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->color('secondary');

        $this->action(function (News $record, Action $action) {
            $record->draft();
            $action->success();
        });

        $this->requiresConfirmation();

        $this->label(__('news.action.change_status.draft.button'));

        $this->modalHeading(__('news.action.change_status.draft.heading'));
        $this->modalSubheading(__('news.action.change_status.draft.subheading'));
        $this->modalButton(__('news.action.change_status.draft.button'));

        $this->successNotificationTitle(__('news.action.change_status.draft.success'));
    }
}
