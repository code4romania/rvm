<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Filament\Resources\VolunteerResource;
use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Volunteer;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListVolunteers extends ListRecords
{
    protected static string $resource = VolunteerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->fields([
                    ImportField::make('organisation_name')
                        ->label(__('volunteer.field.organisation'))
                        ->required(),
                    ImportField::make('first_name')
                        ->label(__('volunteer.field.first_name'))
                        ->required(),
                    ImportField::make('last_name')
                        ->label(__('volunteer.field.last_name'))
                        ->required(),
                    ImportField::make('email')
                        ->label(__('volunteer.field.email'))
                        ->required(),
                    ImportField::make('phone')
                        ->label(__('volunteer.field.phone')),
                    ImportField::make('cnp')
                        ->label(__('volunteer.field.cnp')),
                    ImportField::make('role')
                        ->label(__('volunteer.field.role'))
                        ->required(),
                    ImportField::make('specializations')
                        ->label(__('volunteer.field.specializations'))
                        ->required(),
                    ImportField::make('has_first_aid_accreditation')
                        ->label(__('volunteer.field.has_first_aid_accreditation')),
                    ImportField::make('county')
                        ->label(__('general.county')),
                    ImportField::make('city')
                        ->label(__('general.city')),
                ])
                ->handleRecordCreation(function (array $data) {
                    if (! isset($data['organisation_name']) ||
                        ! isset($data['first_name']) ||
                        ! isset($data['last_name']) ||
                        ! isset($data['email']) ||
                        ! isset($data['role']) ||
                        ! isset($data['specializations'])) {
                        return new Volunteer();
                    }

                    $organisation = Organisation::query()
                        ->where('name', 'like', $data['organisation_name'])
                        ->first();

                    if (! $organisation) {
                        return new Volunteer();
                    }

                    $roles = VolunteerRole::options();
                    $role = array_search($data['role'], $roles);

                    $specializations = explode(',', $data['specializations']);
                    $allSpecializations = VolunteerSpecialization::options();
                    $newSpecializations = [];
                    foreach ($specializations as $specialization) {
                        $newSpecializations[] = array_search(trim($specialization), $allSpecializations);
                    }

                    $firstAID = false;
                    if (isset($data['has_first_aid_accreditation'])) {
                        $firstAID = (bool) $data['has_first_aid_accreditation'];
                    }

                    $fields = ['organisation_id' => $organisation->id,
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? null,
                        'cnp' => $data['cnp'] ?? null,
                        'role' => $role,
                        'specializations' => array_filter($newSpecializations),
                        'has_first_aid_accreditation' => $firstAID,
                    ];

                    if (isset($data['county'])) {
                        $county = County::query()
                            ->where('name', 'like', $data['county'])
                            ->first();

                        if ($county) {
                            $fields['county_id'] = $county->id;

                            if ($data['city']) {
                                $city = City::query()
                                    ->search($data['city'])
                                    ->where('county_id', $county->id)
                                    ->first();

                                if ($city) {
                                    $fields['city_id'] = $city->id;
                                }
                            }
                        }
                    }

                    return Volunteer::create($fields);
                })
                ->label(__('volunteer.field.import')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('organisation');
    }
}
