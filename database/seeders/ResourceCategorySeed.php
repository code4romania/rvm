<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Filament\Forms\FieldGroups;
use App\Models\Resource\Category;
use Illuminate\Database\Seeder;

class ResourceCategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Adăpostire',
                'children' => [
                    [
                        'name' => 'Corturi',
                        'types' => [
                            'Iarnă',
                            'Vară',
                            'Gonflabil',
                            'Pe structură metalică',
                            'Utilat',
                            'Neutilat',
                            'Altul',
                        ],
                        'field_group' => FieldGroups\TentFieldGroup::class,
                    ],
                    [
                        'name' => 'Rulote',
                        'field_group' => FieldGroups\TrailerFieldGroup::class,
                    ],
                    [
                        'name' => 'Cazare',
                    ],
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
            [
                'name' => 'Transport',
                'children' => [
                    [
                        'name' => 'Rutier',
                        'types' => [
                            'Mașină',
                            'Dubă',
                            'Camion',
                            'Altul',
                        ],
                        'field_group' => FieldGroups\VehicleFieldGroup::class,
                    ],
                    [
                        'name' => 'Maritim',
                        'field_group' => FieldGroups\BoatFieldGroup::class,
                    ],
                    [
                        'name' => 'Feroviar',
                    ],
                    [
                        'name' => 'Aerian',
                        'field_group' => FieldGroups\AircraftFieldGroup::class,
                    ],
                    [
                        'name' => 'Altul',
                    ],
                ],

            ],
            [
                'name' => 'Salvare',
                'children' => [
                    [
                        'name' => 'Câini utilitari',
                        'types' => [
                            'Căutare în mediul urban',
                            'Căutare în mediu natural',
                            'Altul',
                        ],
                        'field_group' => FieldGroups\RescueDogFieldGroup::class,
                    ],
                ],
            ],
            [
                'name' => 'Telecomunicații',
                'children' => [
                    [
                        'name' => 'Radiocomunicații',
                        'field_group' => FieldGroups\RadioFieldGroup::class,
                        'custom_attributes' => [
                            [
                                'name' => 'tech_type',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Televiziune',
                        'field_group' => FieldGroups\TvFieldGroup::class,
                        'custom_attributes' => [
                            [
                                'name' => 'area',
                                'type' => 'select',
                                'options' => [
                                    'Nationala',
                                    'Locala',
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Radiodifuziune',
                        'field_group' => FieldGroups\BroadcastFieldGroup::class,
                    ],
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
            [
                'name' => 'IT&C',
                'children' => [
                    [
                        'name' => 'Hardware',
                    ],
                    [
                        'name' => 'Software',
                    ],
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
            [
                'name' => 'Altele',
                'children' => [
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
        ];

        foreach ($categories as $item) {
            $category = Category::create([
                'name' => $item['name'],
            ]);

            foreach ($item['children'] as $child) {
                $subcategory = $category->subcategories()->create([
                    'name' => $child['name'],
                    'field_group' => data_get($child, 'field_group'),
                ]);

                $subcategory->types()->createMany(
                    collect($child['types'] ?? [])
                        ->map(fn ($type) => [
                            'name' => $type,
                        ])
                );
            }
        }
    }
}
