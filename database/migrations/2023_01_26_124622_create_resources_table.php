<?php

declare(strict_types=1);

use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Resource\Category;
use App\Models\Resource\Subcategory;
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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Subcategory::class)->constrained()->cascadeOnDelete();
            $table->string('resource_type');
            $table->json('attributes')->nullable();
            $table->string('name');
            $table->integer('quantity');
            $table->enum('type', ['tip1', 'tip2']);
            $table->boolean('has_transport')->default(false);
            $table->foreignIdFor(County::class)->nullable()->constrained();
            $table->foreignIdFor(City::class)->nullable()->constrained();
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->text('observation')->nullable();

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
        Schema::dropIfExists('resources');
    }
};
