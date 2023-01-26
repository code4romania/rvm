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
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cnp')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('accreditation');
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
