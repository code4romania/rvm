<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerResource\Actions;

use App\Enum\VolunteerRole;
use App\Enum\VolunteerSpecialization;
use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Volunteer;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rule;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ImportVolunteersAction extends ImportAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('volunteer.labels.import'));
        $this->successNotificationTitle(__('volunteer.field.success_import'));
        $this->visible(fn () => auth()->user()->isPlatformAdmin() || auth()->user()->isOrgAdmin());
        $this->handleBlankRows(true);
        $this->fields([
            Select::make('organisation_id')
                ->options(function () {
                    if (auth()->user()->isOrgAdmin()) {
                        $organisation = auth()->user()->organisation;

                        return [$organisation->id => $organisation->name];
                    }

                    return Organisation::all()
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->label(__('organisation.label.singular'))
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
        ]);

        $this->handleRecordCreation(function (array $data) {
            $validator = \Validator::make($data, [
                'organisation_id' => [
                    'required',
                    Rule::in(Organisation::all()->pluck('id')),
                ],
                'first_name' => [
                    'required',
                    'max:255',
                    'string',
                ],
                'last_name' => [
                    'required',
                    'max:255',
                    'string',
                ],
                'email' => [
                    'required',
                    'max:255',
                    'email',
                ],
                'phone' => [
                    'numeric',
                    'min:10',
                ],
                'role' => [
                    'required',
                    Rule::in(VolunteerRole::options()),
                ],
                'specializations' => ['required'],
            ]);

            $data = $validator->getData();
            $messages = $validator->getMessageBag();
            if ($messages->messages()) {
                $this->setFailureMsg($messages->messages());

                return new Volunteer();
            }
            $roles = VolunteerRole::options();
            $role = array_search($data['role'], $roles);

            $specializations = explode(',', $data['specializations']);
            $allSpecializations = VolunteerSpecialization::options();
            $newSpecializations = [];
            foreach ($specializations as $specialization) {
                $specializationFromEnum = array_search(trim($specialization), $allSpecializations);
                if (! $specializationFromEnum) {
                    $this->setFailureMsg([
                        __(
                            'validation.in_array',
                            ['attribute' => __('volunteer.field.specializations') . ' (' . $specialization . ')',
                                'other' => implode(', ', $allSpecializations),
                            ]
                        ),
                    ]);

                    return new Volunteer();
                }
                $newSpecializations[] = $specializationFromEnum;
            }

            $firstAID = false;
            if (isset($data['has_first_aid_accreditation'])) {
                $firstAID = (bool) $data['has_first_aid_accreditation'];
            }

            $fields = ['organisation_id' => $data['organisation_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'cnp' => $data['cnp'] ?? null,
                'role' => $role,
                'specializations' => $newSpecializations,
                'has_first_aid_accreditation' => $firstAID,
            ];

            if (isset($data['county'])) {
                $county = County::query()
                    ->where('name', 'like', trim($data['county']))
                    ->first();

                if (! $county) {
                    $this->setFailureMsg([
                        __(
                            'validation.in_array',
                            [
                                'attribute' => __('general.county') . ' (' . $data['county'] . ')',
                                'other' => County::all()
                                    ->map(fn ($item) => $item->name)
                                    ->implode(', '),
                            ]
                        ),
                    ]);

                    return new Volunteer();
                }
                $fields['county_id'] = $county->id;

                if (isset($data['city'])) {
                    $city = City::query()
                        ->search(trim($data['city']))
                        ->where('county_id', $county->id)
                        ->first();

                    if (! $city) {
                        $this->setFailureMsg([
                            __(
                                'validation.in_array',
                                [
                                    'attribute' => __('general.city') . ' (' . $data['city'] . ')',
                                    'other' => __(
                                        'general.localities_from_county',
                                        ['county' => $data['county']]
                                    ),
                                ]
                            ),
                        ]);

                        return new Volunteer();
                    }
                    $fields['city_id'] = $city->id;
                }
            }

            return Volunteer::create($fields);
        });
    }

    public function setFailureMsg(array $messages)
    {
        foreach ($messages as &$msg) {
            $msg = \is_array($msg) ? implode(' ', $msg) : $msg;
        }
        $msgString = implode(' ', $messages);
        $msgString = substr($msgString, 0, 100);
        $this->failureNotificationTitle($msgString);
        $this->failure();
    }
}
