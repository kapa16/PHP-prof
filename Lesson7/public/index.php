<?php

use App\App;

$config = include __DIR__. '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

App::getInstance()->run($config);

