<?php

declare(strict_types=1);

use App\Models\City;
use App\Models\County;
use App\Models\Organisation;
use App\Models\Resource\Category;
use App\Models\Resource\Subcategory;
use App\Models\Resource\Type;
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
            $table->timestamps();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained('resource_categories')->cascadeOnDelete();
            $table->foreignIdFor(Subcategory::class)->constrained('resource_subcategories')->cascadeOnDelete();
            $table->foreignIdFor(Type::class)->nullable()->constrained('resource_subcategory_types')->cascadeOnDelete();
            $table->string('other_type')->nullable();
            $table->json('properties')->nullable();
            $table->string('name');
            $table->foreignIdFor(County::class)->nullable()->constrained();
            $table->foreignIdFor(City::class)->nullable()->constrained();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('comments')->nullable();
        });
    }
};
