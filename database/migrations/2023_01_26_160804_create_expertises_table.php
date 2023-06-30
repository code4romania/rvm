<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Models\Organisation\Expertise;
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
        Schema::create('expertises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('expertise_organisation', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Expertise::class)->constrained()->cascadeOnDelete();
        });

        Expertise::insert([
            ['name' => 'Pregătire/Prevenire'],
            ['name' => 'Intervenție'],
            ['name' => 'Cercetare'],
            ['name' => 'Reconstrucție/Reziliență'],
        ]);
    }
};
