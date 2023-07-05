<?php

declare(strict_types=1);

namespace Database\Seeders;

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
                            'Iarna',
                            'Vara',
                            'Gonflabil',
                            'Pe structura metalică',
                            'Utilat',
                            'Neutilat',
                            'Altul',
                        ],
                        'custom_attributes' => [
                            [
                                'name' => 'dimension',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'relocation_resource',
                                'type' => 'checkbox',
                            ],
                            [
                                'name' => 'has_transport',
                                'type' => 'checkbox',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Rulote',
                        'custom_attributes' => [
                            [
                                'name' => 'dimension',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                        ],
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
                            'Masina',
                            'Duba',
                            'Camion',
                            'Altele',
                        ],
                        'custom_attributes' => [
                            [
                                'name' => 'dimension',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Maritim',
                        'custom_attributes' => [
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Feroviar',
                        'custom_attributes' => [
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Aerian',
                        'custom_attributes' => [
                            [
                                'name' => 'capacity',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'quantity',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Altele',
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
                        ],
                        'custom_attributes' => [
                            [
                                'name' => 'dog_name',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'volunteer_name',
                                'type' => 'text',
                            ],
                            [
                                'name' => 'volunteer_specialization',
                                'type' => 'text',
                            ],

                            [
                                'name' => 'has_trailer',
                                'type' => 'checkbox',
                            ],
                            [
                                'name' => 'has_carriage',
                                'type' => 'checkbox',
                            ],
                            [
                                'name' => 'has_transport',
                                'type' => 'checkbox',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
            [
                'name' => 'Telecomunicații',
                'children' => [
                    [
                        'name' => 'Radiocomunicații',
                        'custom_attributes' => [
                            [
                                'name' => 'tech_type',
                                'type' => 'text',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Televiziune',
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
                    'custom_attributes' => data_get($child, 'custom_attributes'),
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
