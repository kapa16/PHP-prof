<?php

namespace App;

use App\Traits\SingletonTrait;
use RuntimeException;

class App
{
    use SingletonTrait;

    public function __invoke()
    {
        $path = $_REQUEST['path'] ?? '';
        $params = [];
        foreach (explode('/', $path) as $item) {
            if(!$item) {
                continue;
            }
            $params[] = $item;
        }
        $api = '';
        if (!empty($params[0]) && $params[0] === 'api') {
            $api = 'Api\\';
            array_shift($params);
        }
        $controller = $params[0] ?? 'index';
        $method = $params[1] ?? 'index';
        $controllerName = 'App\\Controllers\\' .  $api . ucfirst($controller) . 'Controller';

        if(!class_exists($controllerName)) {
            throw new RuntimeException('Контроллер не найден');
        }
        $controller = new $controllerName;
        if(!method_exists($controller, $method)) {
            throw new RuntimeException('Метод не найден');
        }
        $controller->data = $_REQUEST;
        $controller->$method();
    }
}