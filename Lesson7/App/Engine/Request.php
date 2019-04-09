<?php


namespace App\Engine;


class Request
{
    private $requestString;
    private $requestMethod;
    private $controllerName;
    private $methodName;
    private $api;
    private $params;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->requestString = $_REQUEST['path'] ?? '';
        $this->parseRequest();
    }

    private function escapeInput($data)
    {
        if (is_array($data)) {
            foreach ($data as $value) {
                $this->escapeInput($value);
            }
        } elseif (is_string($data)) {
            return strip_tags($data);
        }
        return $data;
    }

    private function fillParams($requestMethod, array $data = []): void
    {
        $this->escapeInput($data);
        foreach ($data as $key => $value) {
            $this->params[$requestMethod][$key] = $value;
        }
    }

    public function parseRequest(): void
    {
        $params = [];
        foreach (explode('/', $this->requestString) as $item) {
            if (!$item) {
                continue;
            }
            $params[] = $item;
        }

        $this->api = '';
        if (!empty($params[0]) && $params[0] === 'api') {
            $this->api = 'Api\\';
            array_shift($params);
        }

        $controller = $params[0] ?? 'index';
        $this->methodName = $params[1] ?? 'index';
        $this->controllerName = 'App\\Controllers\\' . $this->api . ucfirst($controller) . 'Controller';

        $this->fillParams('post', $_POST);
        $this->fillParams('get', $_GET);
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getApiMode()
    {
        return $this->api;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function get(string $name = '')
    {
        if (empty($name)) {
            return $this->params['get'];
        }
        return $this->params['get'][$name] ?? null;
    }

    public function post(string $name = '')
    {
        if (empty($name)) {
            return $this->params['post'];
        }
        return $this->params['post'][$name] ?? null;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function isGet(): bool
    {
        return $this->requestMethod === 'GET';
    }

    public function isPost(): bool
    {
        return $this->requestMethod === 'POST';
    }
}