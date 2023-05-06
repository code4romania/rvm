<?php

declare(strict_types=1);

use App\Enum\VolunteerRole;
use App\Models\Organisation;
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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('role', VolunteerRole::values());
            $table->foreignIdFor(\App\Models\City::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\County::class)->nullable()->constrained();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cnp')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('accreditation')->nullable();
            $table->string('specialization')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volunteers');
    }
};
