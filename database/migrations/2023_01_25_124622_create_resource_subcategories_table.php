<?php

declare(strict_types=1);

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
        Schema::create('resource_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('resource_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('resources_type')->nullable();
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
        Schema::dropIfExists('resource_subcategories');
    }
};
