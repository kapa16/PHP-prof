<?php

namespace App;

use App\Controllers\IndexController;
use RuntimeException;

class App
{
    public function __invoke()
    {
        try {
            $path = $_REQUEST['path'] ?? '';

            $params = [];
            foreach (explode('/', $path) as $item) {
                if (!$item) {
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
            $controllerName = 'App\\Controllers\\' . $api . ucfirst($controller) . 'Controller';

            if (!class_exists($controllerName)) {
                throw new RuntimeException('Контроллер не найден');
            }
            $controller = new $controllerName;
            if (!method_exists($controller, $method)) {
                throw new RuntimeException('Метод не найден');
            }

            $controller->data = $_REQUEST;
            $result = $controller->$method();

            if ($api) {
                $result = [
                    'data'       => $result,
                    'error'      => false,
                    'error_text' => '',
                    'location'   => $controller->locationRedirect
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                echo $result;
            }
        } catch (RuntimeException $e) {
            if ($api) {
                $result = [
                    'data'       => null,
                    'error'      => true,
                    'error_text' => $e->getMessage(),
                    'location'   => $controller->locationRedirect
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                echo (new IndexController())->error(['error' => $e->getMessage()]);
            }
        }
    }
}