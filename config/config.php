<?php
return [
    'namespaces' => [
        'App' => '{root}/app',
        'Koks' => '{root}/vendor/koks',
    ],
    'middlewareNamespace' => 'App\Middleware',
    'viewsUrls' => [
        '{root}/public/themes/{theme}/views/{view}.php',
        '{root}/public/views/{view}.php',
        '{root}/public/themes/{themes}/errors/404.php',
        '{root}/public/views/errors/404.php',
    ],
    'localeNameTemplate' => '{root}/app/Locales/{lang}.php',
];
