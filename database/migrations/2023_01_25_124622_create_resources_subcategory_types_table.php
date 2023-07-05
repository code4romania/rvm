<?php

declare(strict_types=1);

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
        Schema::create('resource_subcategory_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subcategory::class)->constrained('resource_subcategories')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });
    }
};
