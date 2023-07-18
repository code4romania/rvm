<?php

declare(strict_types=1);

return [

    'greeting' => [
        'error' => 'Oops!',
        'hello' => 'Salut!',
    ],

    'subcopy' => 'Dacă nu poți apăsa pe butonul ":actionText", copiază adresa de mai jos în browser-ul tău:',

    'expiring_protocol' => [
        'subject' => 'Protocolul dintre :name și DSU va expira în 30 de zile',
        'line_1' => 'Protocolul dintre :name și Departamentul pentru Situații de Urgență va expira în 30 de zile.',
        'line_2' => 'Dacă acesta nu este prelungit, veți pierde accesul în aplicația de management de resurse.',
    ],

    'expired_protocol' => [
        'subject' => 'Protocolul dintre :name și DSU a expirat',
        'line' => 'Protocolul dintre :name și Departamentul pentru Situații de Urgență a expirat.',
    ],

    'summary_expiring_protocols' => [
        'subject' => ':count protocoale vor expira în 30 de zile',
        'view' => 'Vezi listă',
    ],

    'summary_expired_protocols' => [
        'subject' => ':count protocoale au expirat',
        'view' => 'Vezi listă',
    ],
];
