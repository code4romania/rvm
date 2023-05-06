<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Organisation;
use App\Models\Organisation\Expertise;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory(['email' => 'admin@example.com'])
            ->create();
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

        Organisation::factory(20)
            ->hasVolunteers(3, function (array $attributes, Organisation $organisation) {
                return ['organisation_id' => $organisation->id];
            })
            ->hasResources(3, function (array $attributes, Organisation $organisation) {
                return ['organisation_id' => $organisation->id];
            })->create();
        Expertise::factory(10)->create();
        Organisation\RiskCategory::factory(10)->create();
        Organisation\ResourceType::factory(10)->create();
    }
}
