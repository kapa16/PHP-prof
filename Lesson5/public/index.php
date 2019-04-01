<?php

require_once '../vendor/autoload.php';

try {
    $app = \App\App::getInstance();
    $app();
} catch (RuntimeException $err) {
    header('Location: /');
}
