<?php

namespace Hetg\LunchGenerator\Router;

class Router
{
    private static $routes = [];

    private static $allowedMethods = ['get', 'post', 'put', 'delete'];

    public static function getRoutes(){
        return self::$routes;
    }

    public static function add(string $method, string $path, string $controller){
        if (in_array(strtolower($method), self::$allowedMethods)){
            self::$routes[$path] = [
                'controller' => $controller,
                'method' => $method
            ];
        } else {
            throw new \Exception('Wrong method');
        }

    }

    public static function resolve(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

        if (!key_exists($path, self::$routes)){
            throw new \Exception('Not Found', 404);
        }

        if (!in_array(strtolower($method), self::$allowedMethods)){
            throw new \Exception('Method Not Allowed', 405);
        }

        [$controller, $action] = explode('::',self::$routes[$path]['controller']);

        $class = new \ReflectionClass($controller);
        $method = $class->getMethod($action);
        $controller = $class->newInstance();

        if (!empty($_REQUEST)){
            $method = $class->getMethod($action);
            $params = $method->getParameters();
            $arguments = [];
            foreach ($params as $param){
                if (($param->isOptional() && isset($_REQUEST[$param->getName()])) || !$param->isOptional()){
                    $arguments[$param->getPosition()] = $_REQUEST[$param->getName()];
                }
            }

            return $method->invokeArgs($controller, array_values($arguments));
        }

        return $method->invoke($controller);
    }

}