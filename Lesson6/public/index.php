<?php

use App\App;

require_once '../vendor/autoload.php';

try {
    $app = App::getInstance();
    $app();
} catch (RuntimeException $err) {
    header('Location: /');
}
