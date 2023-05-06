<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organisation\Expertise;
use App\Models\Organisation\ResourceType;
use App\Models\Organisation\RiskCategory;
use Illuminate\Database\Seeder;

class OrganisationActivitySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expertise = [
            ['name' => 'Prevenție'],
            ['name' => 'Intervenție'],
            ['name' => 'Reconstructie'],
        ];
        Expertise::insert($expertise);
        $riskCategory = [
            ['name'=> 'Inundatii'],
            ['name'=> 'Cutremur'],
            ['name'=> 'Avalanșă'],
            ['name'=> 'Incendiu'],
            ['name'=> 'Accident nuclear'],
            ['name'=> 'Ger-Viscol'],
            ['name'=> 'Furtun'],
            ['name'=> 'Alunecare de teren'],
            ['name'=> 'Conflict armat'],
            ['name'=> 'Tornadă'],
            ['name'=> 'Caniculă'],
            ['name'=> 'Altele'],
        ];
        RiskCategory::insert($riskCategory);
        $resourceType = [
            ['name'=> 'Programe de pregătire în școli'],
            ['name'=> 'Cursuri de prim-ajutor'],
            ['name'=> 'Echipe de căutare'],
            ['name'=> 'Intervenție rapidă'],
            ['name'=> 'Prim ajutor calificat'],
            ['name'=> 'Servicii sociale'],
            ['name'=> 'Ajutor umanitar'],
            ['name'=> 'Altele'],
        ];
        ResourceType::insert($resourceType);
    }
}
