<?php

declare(strict_types=1);

use App\Models\Organisation\ResourceType;
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
        Schema::create('resource_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        ResourceType::insert([
            ['name' => 'Pregătire/Prevenire: Programe de informare'],
            ['name' => 'Pregătire/Prevenire: Programe de conștientizare'],
            ['name' => 'Pregătire/Prevenire: Cursuri de prim ajutor'],
            ['name' => 'Pregătire/Prevenire: Altele'],
            ['name' => 'Intervenție: Echipe de căutare'],
            ['name' => 'Intervenție: Intervenție rapidă'],
            ['name' => 'Intervenție: Prim ajutor calificat'],
            ['name' => 'Intervenție: Servicii sociale'],
            ['name' => 'Intervenție: Asistență psihologică'],
            ['name' => 'Intervenție: Ajutor umanitar'],
            ['name' => 'Intervenție: Altele'],
            ['name' => 'Cercetare: Analiză'],
            ['name' => 'Cercetare: Mapare'],
            ['name' => 'Cercetare: Evaluare'],
            ['name' => 'Cercetare: Altele'],
            ['name' => 'Reconstrucție/Reziliență: Reconstrucție infrastructură'],
            ['name' => 'Reconstrucție/Reziliență: Altele'],
            ['name' => 'Altele'],
        ]);
    }
};
