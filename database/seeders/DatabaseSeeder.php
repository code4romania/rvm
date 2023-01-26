<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Expertise;
use App\Models\Organisation;
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
        Organisation::factory(20)->hasVolunteers(3, function (array $attributes, Organisation $organisation) {
            return ['organisation_id' => $organisation->id];
        })->create();
        Expertise::factory(10)->create();
    }
}
