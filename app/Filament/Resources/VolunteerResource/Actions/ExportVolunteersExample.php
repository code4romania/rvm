<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Actions;

use App\Models\Volunteer;
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
                    Column::make('first_name'),
                    Column::make('last_name'),
                    Column::make('email'),
                    Column::make('phone'),
                    Column::make('cnp'),
                    Column::make('rol'),
                    Column::make('specialisations'),
                    Column::make('has_first_aid_accreditation'),
                    Column::make('county')
                        ->formatStateUsing(fn ($state) => $state->name),
                    Column::make('city')
                        ->formatStateUsing(fn ($state) => $state->name),
                ]),
        ]);
    }

    public function handleExport(array $data)
    {
        $examples = Volunteer::factory()
            ->count(10)
            ->make()
            ->each(function ($item) {
                $item->load('county');
                $item->load('city');

                return $item;
            });

        $examplesArray = [];
        foreach ($examples as $example) {
            $examplesArray[] = $example;
        }

        $exportable = $this->getSelectedExport($data);
        $livewire = $this->getLivewire();

        return app()->call([$exportable, 'hydrate'], [
            'livewire' => $this->getLivewire(),
            'records' => $examples,
            'formData' => data_get($data, $exportable->getName()),
        ])->export();
    }
}
