<?php

namespace App;

use App\Controllers\IndexController;
use App\Engine\Db;
use App\Engine\Request;
use App\Engine\Session;
use App\Models\Repositories\Repository;
use App\Traits\SingletonTrait;
use App\Views\Templater;
use RuntimeException;

/**
 * @property Request request
 * @property Db db
 * @property Templater render
 * @property Session session
 * @property string templateDir
 */
class App
{
    use SingletonTrait;

    private $config;
    private $repositories = [];
    private $components = [];

    public static function call(): self
    {
        return self::getInstance();
    }

    public function getRepository(string $repositoryName): Repository
    {
        $repositoryClass = 'App\\Models\\Repositories\\' . $repositoryName . 'Repository';
        if (!class_exists($repositoryClass)) {
            throw new RuntimeException('Unknown class');
        }
        if (empty($this->repositories[$repositoryClass])) {
            $this->repositories[$repositoryClass] = new $repositoryClass();
        }
        return $this->repositories[$repositoryClass];
    }

    private function createComponent($name)
    {
        $component = $this->config['components'][$name];
        if (empty($component)) {
            throw new RuntimeException("Component {$name} not found");
        }
        $className = $component['class'];
        if (!class_exists($className)) {
            throw new RuntimeException("Not found class for {$name} component");
        }
        unset($component['class']); //Parameters for component except class name
        return new $className($component);

    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
    }

    public function __get($name)
    {
        if (empty($this->components[$name])) {
            $this->components[$name] = $this->createComponent($name);
        }
        return $this->components[$name];
    }

    public function __isset($name)
    {
        return isset($this->components[$name]);
    }

    public function getConfig($name)
    {
        if (empty($this->config[$name])) {
            throw new RuntimeException('No config named ' . $name);
        }
        return $this->config[$name];
    }

    public function run($config): void
    {
        $this->config = $config;
        $this->runController();
    }

    private function runController(): void
    {
        try {
            $controllerName = $this->request->getControllerName();
            $method = $this->request->getMethodName();
            $api = $this->request->getApiMode();

            if (!class_exists($controllerName)) {
                throw new RuntimeException('Контроллер не найден');
            }
            $controller = new $controllerName;
            if (!method_exists($controller, $method)) {
                throw new RuntimeException('Метод не найден');
            }

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