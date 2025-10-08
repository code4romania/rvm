<?php
return [
    'label' => [
        'singular' => 'Resource',
        'plural' => 'Logistics and Expertise',
    ],
    'fields' => [
        'name' => 'Resource Name',
        'localisation' => 'Localisation',
        'contact' => 'Contact person',
        'contact_phone' => 'Phone number',
        'attributes' => 'Resource attributes',
        'comments' => 'Comments',
        'category' => 'Category',
        'subcategory' => 'Subcategory',
        'type' => 'Type',
        'type_other' => 'Other type',
        'organisation' => 'Organization',
    ],
    'attributes' => [
        'location' => [
            'relocatable' => 'Resource relocation',
            'transportable' => 'Transportation available',
        ],
        'coverage' => [
            'label' => 'Coverage',
            'national' => 'National coverage',
            'local' => 'Local coverage',
        ],
        'dimensions' => 'Dimensions',
        'capacity' => 'Capacity',
        'quantity' => 'Number of available units',
        'dog_name' => 'Dog\'s name',
        'volunteer_name' => 'Volunteer name',
        'volunteer_specialization' => 'Volunteer\'s area of expertise',
        'dog_aircraft_cage' => 'Air cargo cage',
        'dog_trailer' => 'Dog transport vehicle trailer',
        'type' => [
            'tent' => 'Type of tent',
            'trailer' => 'Type of trailer',
            'vehicle' => 'Type of vehicle',
            'boat' => 'Type of vessel',
            'aircraft' => 'Type of aircraft',
            'dog' => 'Type of dog',
            'radio' => 'Equipment type',
        ],
    ],
    'modal' => [
        'heading' => 'Add a new resource',
        'subheading' => 'Add a new resource using the form below',
    ],
];
