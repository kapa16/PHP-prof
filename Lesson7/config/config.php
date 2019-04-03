<?php

session_start();

define('DB_DRIVER', 'mysql');
define('DB_NAME', 'geek_brains_shop');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

define('SITE_DIR', __DIR__ . '/../');
define('TEMPLATE_DIR', SITE_DIR . 'templates');