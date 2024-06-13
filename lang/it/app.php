<?php

declare(strict_types=1);

return [
    'backoffice' => [
        'index' => [
            'title' => 'Shorts',
            'color' => '#10B9C4',
            'icon' => 'fa-link',
        ],
        'tags' => [
            'title' => 'Tags',
            'link_copied' => 'Link copiato',
            'color' => '#10B9C4',
            'icon' => 'fa-link',
        ],
        // 'users' => [
        //     'title' => 'Gestione Utenti',
        //     'color' => '#10B9C4',
        //     'icon' => 'fa-user-group',
        // ],
    ],
    // 'menu' => [
    //     'examples' => [
    //         'title' => 'Gestione Esempi',
    //         'icon' => 'fa-ticket',
    //     ],
    // ],
    'pages' => [
        'login' => [
            'meta_title' => 'Login',
            'meta_description' => 'Login',
            
            'title' => 'Login',
            'login_button' => 'Accedi',
            'error' => 'Errore',
        ],
        'index' => [
            'meta_title' => 'Home',
            'meta_description' => 'Questa è la homepage',
            
            'link_copied' => 'Link copiato',
        ],
        'short' => [
            'share' => 'Condividi',
            'copy_link' => 'Copia Link',
            'urls' => 'URLs',
            'timeline' => 'Timeline',
            'map' => 'Mappa',
        ],
        'maintenance' => [
            'meta_title' => 'Manutenzione',
            'meta_description' => 'Il sito è in manutenzione',
            
            'title' => 'Il sito è in manutenzione',
            'subtitle' => 'Stiamo lavorando per offrirti il miglior servizio possibile'
        ],
    ]
];
