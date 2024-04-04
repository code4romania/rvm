<?php

declare(strict_types=1);

return [

    'label' => [
        'singular' => 'Resursă',
        'plural' => 'Logistică și Expertiză',
    ],

    'fields' => [
        'name' => 'Denumire resursă',
        'localisation' => 'Localizare',
        'contact' => 'Persoana de contact',
        'contact_phone' => 'Telefon de contact',
        'attributes' => 'Atribute resursă',
        'comments' => 'Comentarii',
        'category' => 'Categorie',
        'subcategory' => 'Subcategorie',
        'type' => 'Tip',
        'type_other' => 'Alt tip',
        'organisation' => 'Organizație',
    ],

    'attributes' => [

        'location' => [
            'relocatable' => 'Relocare resursă',
            'transportable' => 'Transport disponibil',
        ],

        'coverage' => [
            'label' => 'Acoperire',
            'national' => 'Acoperire națională',
            'local' => 'Acoperire locală',
        ],

        'dimensions' => 'Dimensiuni',
        'capacity' => 'Capacitate',
        'quantity' => 'Nr. de bucăți disponibile',

        'dog_name' => 'Nume câine',
        'volunteer_name' => 'Nume voluntar',
        'volunteer_specialization' => 'Specializare voluntar',
        'dog_aircraft_cage' => 'Cușcă transport aerian',
        'dog_trailer' => 'Remorcă transport câini',

        'type' => [
            'tent' => 'Tip de cort',
            'trailer' => 'Tip de rulotă',
            'vehicle' => 'Tip de autovehicul',
            'boat' => 'Tip de ambarcațiune',
            'aircraft' => 'Tip de aeronavă',
            'dog' => 'Tip de câine',
            'radio' => 'Tip tehnică',
        ],

    ],

    'modal' => [
        'heading' => 'Adaugă o resursa nouă',
        'subheading' => 'Adauga o resursa noua folosind formularul de mai jos',
    ],
];
