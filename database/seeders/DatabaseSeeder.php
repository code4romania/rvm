<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\County;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        Mail::fake();

        User::factory(['email' => 'admin@example.com'])
            ->platformAdmin()
            ->create();

        $counties = County::all();

        User::factory()
            ->platformCoordinator()
            ->count($counties->count())
            ->sequence(
                ...$counties->map(fn (County $county) => [
                    'first_name' => 'Coordonator',
                    'last_name' => $county->name,
                    'email' => Str::slug($county->name) . '@example.com',
                    'county_id' => $county->id,
                ])->toArray()
            )
            ->create();

        $this->call([
            ResourceCategorySeed::class,
        ]);

        Organisation::factory()
            ->count($counties->count() * 5)
            ->sequence(
                ...$counties->map(fn (County $county) => [
                    'county_id' => $county->id,
                ])->toArray()
            )
            ->createQuietly();
    }
}
