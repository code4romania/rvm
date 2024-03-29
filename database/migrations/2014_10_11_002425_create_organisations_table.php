<?php

declare(strict_types=1);

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('type');
            $table->string('ngo_type')->nullable();
            $table->string('status')->default('inactive');
            $table->string('email');
            $table->string('phone');
            $table->year('year')->nullable();
            $table->string('cif')->nullable()->unique();
            $table->string('registration_number')->nullable();
            $table->foreignIdFor(County::class)->nullable()->constrained();
            $table->foreignIdFor(City::class)->nullable()->constrained();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->json('contact_person')->nullable();
            $table->json('other_information')->nullable();
            $table->string('type_of_area')->nullable();
            $table->json('areas')->nullable();
            $table->boolean('has_branches')->default(false);
            $table->boolean('social_services_accreditation')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
