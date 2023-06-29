<?php

declare(strict_types=1);

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
        Schema::create('risk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        RiskCategory::insert([
            ['name' => 'Inundații'],
            ['name' => 'Cutremur'],
            ['name' => 'Avalanșă'],
            ['name' => 'Incendiu'],
            ['name' => 'Accident CBRN'],
            ['name' => 'Fenomene meteo extreme (furtună, caniculă, tornadă, uragan)'],
            ['name' => 'Alunecări de teren'],
            ['name' => 'Conflict armat'],
            ['name' => 'Altele'],
        ]);
    }
};
