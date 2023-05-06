<?php

declare(strict_types=1);

return [
    'modal' => [
        'heading' => 'Adaugă organizație nouă',
        'subheading' => 'Trimite o invitatie către  organizații folosind câmpurile de mai jos. ',
    ],
    'field' => [
        'name' => 'Denumirea organizatiei',
        'alias' => 'Alias organizatie',
        'type' => 'Tipul organizatiei:',
        'email_organisation' => 'Email de contact organizatie',
        'phone_organisation' => 'Telefon de contact organizatie',
        'year' => 'Anul infiintarii',
        'vat' => 'CUI/CIF',
        'no_registration' => 'Nr Registrul Asociatiilor si Fundatiilor',
        'intervention_type' => 'Tip intervenție ',
        'agreement_doc' => 'Protocol de colaborare',
        'description' => 'Descriere extinsă organizație',
        'short_description' => 'Descriere scurtă organizație',
        'logo' => 'Logo organizație',
        'contact_person' => 'Persoana de contact in relația cu Departamentul pentru Situații de Urgență',
        'choose' => 'Alege',
        'email' => 'Email',
        'phone' => 'Telefon',
        'contact_person_name' => 'Nume și prenume persoana de contact',
        'other_information' => 'Alte informații',
        'social_services_accreditation' => 'ONG acreditat pentru servicii sociale',
        'risk_categories' => 'Categorii de risc',
        'website' => 'Website',
        'facebook' => 'Facebook',
        'has_branches' => 'Are filiale?',
        'resource_types' => 'Tipuri de resurse',
        'type_of_area' => 'Tipul de aria de activitate',
        'risk_category' => 'Categorie de risc',
        'expertises' => 'Expertiza',
        'branches' => 'Filiale',
        'address' => 'Adresa',
        'status' => 'Status',
        'status_action' => [
            'active' => 'Dezactiveaza',
            'inactive' => 'Activeaza'
        ],
        'types' => [
            'association' => 'Asociatie',
            'foundation' => 'Fundatie',
            'federation' => 'Federatie',
            'informal_group' => 'Grup informal',
        ],
        'area_of_activity' => [
            'types' => [
                'local' => 'Local',
                'regional' => 'Regional',
                'national' => 'National',
                'international' => 'International',
            ],
            'areas' => 'Localitați',
            'help_text' => 'Poți adăuga mai multe arii de activitate',
            'add_area' => 'Adaugă aria de activitate',
        ],
        'branch' => [
            'help_text' => 'Poți adăuga mai multe filiale',
            'add_area' => 'Adaugă filială',
        ],
        'role' => 'Rol',

    ],
    'section' => [
        'general_data' => 'Date generale',
        'activity' => 'Activitate',
        'volunteers' => 'Voluntari',
        'resource' => 'Resurse',
        'interventions' => 'Interventii',
        'documents' => 'Documente',
        'users' => 'Utilizatori',
        'branches' => 'Filiale',
        'expertises' => 'Expertize',
        'area_of_activity' => 'Aria de activitate',
        'localities' => 'Localitati',
        'other_information' => 'Alte informatii',

    ],
    'help' => [
        'short_description' => 'Descrie organizația ta în 200 - 250 caractere. Descrierea va fi vizibilă în alte aplicații, după caz.',
        'description' => 'Adaugă o descriere a organizației tale (maximum 700 caractere).',
        'logo' => 'Încarcă logo-ul organizației tale, la o calitate cât mai bună.',
    ],

    'action' => [
        'change_status' => [
            'inactive' => [
                'heading' => 'Activeaza organizatia',
                'subheading' => 'Esti sigur ca vrei sa activezi organizatia?',
                'button' => 'Activeaza'
            ],
            'active' => [
                'heading' => 'Dezactiveaza organizatia',
                'subheading' => 'Esti sigur ca vrei sa dezactivezi organizatia?',
                'button' => 'Dezactiveaza'
            ],
        ]
    ]
];
