<?php

declare(strict_types=1);

use App\Models\City;
use App\Models\County;
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
            $table->string('full_name')->virtualAs(<<<'SQL'
                NULLIF(CONCAT_WS(" ", first_name, last_name), " ")
            SQL);
            $table->string('role');
            $table->foreignIdFor(City::class)->nullable()->constrained();
            $table->foreignIdFor(County::class)->nullable()->constrained();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cnp', 13)->nullable();
            $table->json('specializations');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
