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
                    ],
                    [
                        'name' => 'Rulote',
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
                    ],
                    [
                        'name' => 'Maritim',
                    ],
                    [
                        'name' => 'Feroviar',
                    ],
                    [
                        'name' => 'Aerian',
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
                    ],
                    [
                        'name' => 'Radiocomunicații',
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
                $category->subcategories()->create([
                    'name' => $subcategory['name'],
                    'slug' => \Illuminate\Support\Str::slug($subcategory['name']),
                ]);
            }
        }
    }
}
