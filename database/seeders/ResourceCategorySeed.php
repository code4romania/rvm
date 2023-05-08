<?php

declare(strict_types=1);

namespace Database\Seeders;

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
        $resourceCategory = [
            [
                'name' => 'Adăpostire',
                'subcategory' => [
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
                'subcategory' => [
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
                'subcategory' => [
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
                'subcategory' => [
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
                'subcategory' => [
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
                'subcategory' => [
                    [
                        'name' => 'Altele',
                    ],
                ],
            ],
        ];
        foreach ($resourceCategory as $item) {
            $category = \App\Models\Resource\Category::create([
                'name' => $item['name'],
                'slug' => \Illuminate\Support\Str::slug($item['name']),
            ]);
            foreach ($item['subcategory'] as $subcategory) {
                $subcategoryMode = $category->subcategories()->create([
                    'name' => $subcategory['name'],
                    'slug' => \Illuminate\Support\Str::slug($subcategory['name']),
                    'custom_attributes' => $subcategory['custom_attributes'] ?? [],
                ]);
                foreach ($subcategory['types'] ?? [] as $type) {
                    $subcategoryMode->types()->create([
                        'name' => $type,
                        'slug' => \Illuminate\Support\Str::slug($type),
                    ]);
                }
            }
        }
    }
}
