<?php

declare(strict_types=1);

use App\Models\Organisation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('model_has_organisations', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
        });
    }
};
