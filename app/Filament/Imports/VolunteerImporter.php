<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Models\Volunteer;
use App\Rules\ValidCNP;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class VolunteerImporter extends Importer
{
    protected static ?string $model = Volunteer::class;

    public static function getOptionsFormComponents(): array
    {
        return [Select::make('organisation_id')
            ->relationship('organisation', 'name')
            ->placeholder('–')
            ->label(__('volunteer.field.organisation'))
            ->hidden(fn () => auth()->user()->belongsToOrganisation())];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'max:200']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'max:200']),
            ImportColumn::make('role')
                ->requiredMapping()
                ->enum(VolunteerRole::class)
                ->rules(['required', 'max:200']),
            ImportColumn::make('city')
                ->relationship(),
            ImportColumn::make('county')
                ->relationship(),
            ImportColumn::make('email')
                ->rules(['email', 'max:200']),
            ImportColumn::make('phone')
                ->rules(['max:14']),
            ImportColumn::make('cnp')
                ->rules(['max:13', Rule::unique('volunteers', 'cnp'), new ValidCNP]),
            ImportColumn::make('specializations')
                ->requiredMapping()
                ->enum(VolunteerSpecialization::class)
                ->multiple()
                ->rules(['required']),
            ImportColumn::make('has_first_aid_accreditation')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('language')
                ->rules(['max:200']),
        ];
    }

    public function resolveRecord(): Volunteer
    {
        $record = new Volunteer();
        $record->organisation_id = $this->options['organisation_id'];

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your volunteer import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
