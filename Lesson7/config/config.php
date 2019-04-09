<?php
//
//session_start();
//
//define('DB_DRIVER', 'mysql');
//define('DB_NAME', 'geek_brains_shop');
//define('DB_HOST', 'localhost');
//define('DB_USER', 'root');
//define('DB_PASSWORD', '');
//
//define('SITE_DIR', __DIR__ . '/../');
//define('TEMPLATE_DIR', SITE_DIR . 'templates');

return [
    'siteDir' => __DIR__ . '/../',
    'templateDir' => __DIR__ . '/../templates',
    'defaultController' => 'product',
    'components' => [
        'db' => [
            'class' => \App\Engine\Db::class,
            'driver' => 'mysql',
            'host' => 'localhost',
            'login' => 'root',
            'password' => '',
            'database' => 'geek_brains_shop',
            'charset' => 'utf8'
        ],
        'request' => [
            'class' => \App\Engine\Request::class
        ],
        'render' => [
            'class' => \App\Views\Templater::class
        ],
        'session' => [
            'class' => \App\Engine\Session::class
        ]
    ]
];