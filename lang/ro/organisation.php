<?php

declare(strict_types=1);

return [
    'label' => [
        'singular' => 'Organizație',
        'plural' => 'Organizații',
        'profile' => 'Profil organizație',
    ],

    'modal' => [
        'heading' => 'Adaugă organizație nouă',
        'subheading' => 'Trimite o invitatie către  organizații folosind câmpurile de mai jos. ',
    ],
    'field' => [
        'name' => 'Denumire organizație',
        'alias' => 'Alias organizație',
        'type' => 'Tip organizație',
        'email_organisation' => 'Email de contact organizație',
        'phone_organisation' => 'Telefon de contact organizație',
        'year' => 'Anul înființării',
        'cif' => 'CUI/CIF',
        'registration_number' => 'Nr. Registrul Asociațiilor si Fundațiilor',
        'intervention_type' => 'Tip intervenție ',
        'agreement_doc' => 'Protocol de colaborare',
        'description' => 'Descriere extinsă organizație',
        'short_description' => 'Descriere scurtă organizație',
        'logo' => 'Logo organizație',
        'contact_person' => 'Persoană de contact în relația cu DSU',
        'choose' => 'Alege',
        'email' => 'Email',
        'phone' => 'Telefon',
        'contact_person_first_name' => 'Prenume persoană de contact',
        'contact_person_last_name' => 'Nume persoană de contact',
        'other_information' => 'Alte informații',
        'social_services_accreditation' => 'ONG acreditat pentru servicii sociale',
        'risk_categories' => 'Tipuri de riscuri acoperite',
        'website' => 'Website',
        'facebook' => 'Facebook',
        'has_branches' => 'Are filiale?',
        'resource_types' => 'Tipuri de acțiuni',
        'type_of_area' => 'Tipul de aria de activitate',
        'risk_category' => 'Categorie de risc',
        'expertises' => 'Arii de expertiză',
        'branches' => 'Filiale',
        'hq' => 'Sediu',
        'address' => 'Adresa',
        'status' => 'Status',
        'status_action' => [
            'active' => 'Dezactiveaza',
            'inactive' => 'Activeaza',
        ],
        'types' => [
            'association' => 'Asociatie',
            'foundation' => 'Fundatie',
            'federation' => 'Federatie',
            'informal_group' => 'Grup informal',
        ],
        'area' => 'Organizația își desfășoară activitatea pe plan:',
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

    'placeholder' => [
        'name' => 'Introdu denumirea organizației',
        'alias' => 'Introdu aliasul organizației',
        'email' => 'Introdu adresă de email',
        'phone' => 'Introdu număr de telefon',
    ],

    'section' => [
        'organisation_data' => 'Date organizație',
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
        'profile' => 'Profil',
    ],

    'help' => [
        'short_description' => 'Descrie organizația ta în 200 - 250 caractere. Descrierea va fi vizibilă în alte aplicații, după caz.',
        'description' => 'Adaugă o descriere a organizației tale (maximum 1000 caractere).',
        'logo' => 'Încarcă logo-ul organizației tale, la o calitate cât mai bună.',
    ],

    'action' => [
        'change_status' => [
            'inactive' => [
                'heading' => 'Activează organizația',
                'subheading' => 'Ești sigur că vrei să activezi organizația?',
                'button' => 'Activează',
                'success' => 'Organizația a fost activată cu succes.',
            ],
            'active' => [
                'heading' => 'Dezactivează organizația',
                'subheading' => 'Ești sigur că vrei să dezactivezi organizația?',
                'button' => 'Dezactivează',
                'success' => 'Organizația a fost dezactivată cu succes.',
            ],
        ],
    ],
];
