<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Models\Organisation\RiskCategory;
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
        Schema::create('organisation_risk_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(RiskCategory::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
