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
        'ngo_type' => 'Tipul ONG-ului',
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
        'choose' => 'Alege',
        'email' => 'Email',
        'phone' => 'Telefon',
        'contact_person_first_name' => 'Prenume persoană de contact',
        'contact_person_last_name' => 'Nume persoană de contact',
        'contact_person_in_teams_first_name' => 'Nume',
        'contact_person_in_teams_last_name' => 'Prenume',
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
            'ngo' => 'ONG',
            'private' => 'Entitate privată',
            'public' => 'Instituție publică',
            'academic' => 'Mediu academic',
        ],
        'ngo_types' => [
            'association' => 'Asociație',
            'foundation' => 'Fundație',
            'federation' => 'Federație',
        ],
        'area' => 'Organizația își desfășoară activitatea pe plan:',
        'area_of_activity' => [
            'types' => [
                'local' => 'Local',
                'regional' => 'Regional',
                'national' => 'Național',
                'international' => 'Internațional',
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
        'contact_person' => 'Persoană de contact în relația cu DSU',
        'contact_person_in_teams' => 'Persoană de contact pentru Platforma Teams "Sprijin Umanitar DSU-GOV / FiiPregătit"'
    ],

    'help' => [
        'short_description' => 'Descrie organizația ta în 200 - 250 caractere. Descrierea va fi vizibilă în alte aplicații, după caz.',
        'description' => 'Adaugă o descriere a organizației tale (maximum 1500 caractere).',
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
        'resend_invitation' => [
            'button' => 'Retrimite invitație',
            'heading' => 'Retrimite invitație',
            'subheading' => 'Ești sigur că vrei să retrimit invitația?',
            'success' => 'Invitația a fost retimisă.',
            'failure_title' => 'Invitația nu a putut fi trimisă.',
            'failure_body' => 'Acestei organizații i-a fost retrimisă invitația recent. Te rugăm să mai încerci peste o oră.',
        ],
    ],

    'status' => [
        'active' => 'Activ',
        'inactive' => 'Inactiv',
        'invited' => 'Invitat',
    ],
];
