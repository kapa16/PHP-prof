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
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestString = $_REQUEST['path'];
        $this->parseRequest();
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

        $this->params['get'] = $_GET;
        $this->params['post'] = $_POST;
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

    public function get($name)
    {
        return $this->params['get'][$name] ?? null;
    }

    public function post($name)
    {
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