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
            'color' => '#10B9C4',
            'icon' => 'fa-tags',
        ],
        'upload-csv' => [
            'title' => 'Carica CSV',
            'color' => '#10B9C4',
            'icon' => 'fa-file-csv',
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
            'devices' => 'Dispositivi',
            'referrers' => 'Referral',
            'map' => 'Mappa',
            'language_urls' => 'Url in lingua',
            'add_language' => 'Aggiungi Lingua',
        ],
        'tags' => [
            'meta_title' => 'Tags',
            'meta_description' => 'Questa è la pagina delle tag',
            
            'add_tag' => 'Aggiungi Tag',
            'advanced_search' => 'Ricerca Avanzata',
        ],
        'upload-csv' => [
            'meta_title' => 'Carica CSV',
            
            'csv_file' => 'File CSV',
            'template' => 'Template CSV',
            'upload' => 'Carica',
            'upload_statuses' => [
                'success' => 'Tutti gli short sono stati creati',
                'warning' => 'Short creati, :errors codici erano già presenti: :codes'
            ]
        ],
        'maintenance' => [
            'meta_title' => 'Manutenzione',
            'meta_description' => 'Il sito è in manutenzione',
            
            'title' => 'Il sito è in manutenzione',
            'subtitle' => 'Stiamo lavorando per offrirti il miglior servizio possibile'
        ],
    ]
];
