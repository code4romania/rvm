<?php

declare(strict_types=1);

return [

    'label' => [
        'singular' => 'Articol',
        'plural' => 'Articole',

    ],

    'field' => [
        'title' => 'Titlu',
        'body' => 'Continut',
        'status' => 'Status',
        'organisation' => 'Organizație',
        'updated_at' => 'Ultima actualizare',
        'media_files' => 'Atașamente',
        'cover_photo' => 'Poză de coperta'
    ],

    'status' => [
        'drafted' => 'In Draft',
        'published' => 'Publicat',
        'archived' => 'Arhivat',
    ],

    'action' => [
        'change_status' => [
            'draft' => [
                'heading' => 'Mută în Draft',
                'subheading' => 'Ești sigur că vrei sa muți în draft știrea? Odata mutată în draft, aceasta va putea fi din nou editată și publicată pe site-ul DSU, în secțiunea Știri parteneri.',
                'button' => 'Mută în Draft',
                'success' => 'Știrea a fost Mutată în draft.',
            ],
            'publish' => [
                'heading' => 'Publică știrea',
                'subheading' => 'Ești sigur că vrei sa publici știrea? Odata publicată, aceasta va fi afișată pe site-ul DSU, în secțiunea Știri parteneri.',
                'button' => 'Publică',
                'success' => 'Știrea a fost publicată.',
            ],
            'archive' => [
                'heading' => 'Arhivează știrea',
                'subheading' => 'Ești sigur că vrei sa arhivezi știrea? Odata arhivată, aceasta nu va mai fi afișată pe site-ul DSU, în secțiunea Știri parteneri.',
                'button' => 'Arhivează',
                'success' => 'Știrea a fost arhivată.',
            ],
        ]
    ],

];
