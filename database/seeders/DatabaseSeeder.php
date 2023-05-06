<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('production')) {
            Schema::withoutForeignKeyConstraints(function () {
                DB::unprepared(
                    File::get(database_path('data/example_dump.sql'))
                );
            });
            return;
        }
        $user = User::factory(['email' => 'admin@example.com'])
            ->create();
        $this->call([OrganisationActivitySeed::class, ResourceCategorySeed::class]);
        Organisation::factory(20)
            ->hasVolunteers(3, function (array $attributes, Organisation $organisation) {
                return ['organisation_id' => $organisation->id];
            })
            ->hasResources(3, function (array $attributes, Organisation $organisation) {
                return ['organisation_id' => $organisation->id];
            })->create();

    }
}
