<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Actions;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Models\Volunteer;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ExportVolunteersExample extends ExportAction
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->label(__('volunteer.labels.download_example'));

        $this->exports([
            ExcelExport::make()
                ->withColumns([
                    Column::make('first_name')
                        ->heading(__('volunteer.field.first_name')),
                    Column::make('last_name')
                        ->heading(__('volunteer.field.last_name')),
                    Column::make('email')
                        ->heading(__('volunteer.field.email')),
                    Column::make('phone')
                        ->heading(__('volunteer.field.phone')),
                    Column::make('cnp')
                        ->heading(__('volunteer.field.cnp')),
                    Column::make('role')
                        ->heading(__('volunteer.field.role'))
                        ->formatStateUsing(fn (VolunteerRole $state) => $state->label()),
                    Column::make('specializations')
                        ->heading(__('volunteer.field.specializations'))
                        ->formatStateUsing(fn (Collection $state) => $state
                            ->implode(fn (VolunteerSpecialization $item) => $item->label(), ', ')),
                    Column::make('has_first_aid_accreditation')
                        ->heading(__('volunteer.field.has_first_aid_accreditation'))
                        ->formatStateUsing(fn ($state) => $state ? __('general.boolean.yes') : __('general.boolean.no')),
                    Column::make('county')
                        ->heading(__('general.county'))
                        ->formatStateUsing(fn ($state) => $state->name),
                    Column::make('city')
                        ->heading(__('general.city'))
                        ->formatStateUsing(fn ($state) => $state->name),
                ])
                ->withFilename(__('volunteer.file_name.download_example')),
        ]);
    }

    public function handleExport(array $data)
    {
        $examples = Volunteer::query()
            ->with(['county', 'city'])
            ->limit(1)
            ->get();

        $exportable = $this->getSelectedExport($data);

        return app()->call([$exportable, 'hydrate'], [
            'livewire' => $this->getLivewire(),
            'records' => $examples,
            'formData' => data_get($data, $exportable->getName()),
        ])->export();
    }
}
