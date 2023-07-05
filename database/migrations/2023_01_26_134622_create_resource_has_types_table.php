<?php

declare(strict_types=1);

use App\Models\Resource;
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
        Schema::create('resource_has_types', function (Blueprint $table) {
            $table->foreignIdFor(Resource::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Type::class)->constrained('resource_subcategory_types')->cascadeOnDelete();
        });
    }
};
